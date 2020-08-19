<?php
namespace Sx\MessageTest;

use Sx\Message\Message;
use PHPUnit\Framework\TestCase;
use Sx\Message\Stream;

class MessageTest extends TestCase
{
    private const HEADER_NAME_1 = 'name';
    private const HEADER_NAME_2 = 'test';
    private const HEADER_VALUE_1 = 'value';
    private const HEADER_VALUE_2 = 'test';

    private $message;

    protected function setUp(): void
    {
        $this->message = (new Message())
            ->withHeader(self::HEADER_NAME_1, self::HEADER_VALUE_1)
            ->withHeader(self::HEADER_NAME_2, self::HEADER_VALUE_2);
    }

    public function testHasHeader(): void
    {
        self::assertTrue($this->message->hasHeader(strtoupper(self::HEADER_NAME_1)));
        self::assertFalse($this->message->hasHeader('empty'));
    }

    public function testGetHeader(): void
    {
        self::assertEquals([self::HEADER_VALUE_1], $this->message->getHeader(strtoupper(self::HEADER_NAME_1)));
        self::assertEmpty($this->message->getHeader('empty'));
    }

    public function testGetHeaders(): void
    {
        self::assertEquals(
            [
                self::HEADER_NAME_1 => [self::HEADER_VALUE_1],
                self::HEADER_NAME_2 => [self::HEADER_VALUE_2],
            ],
            $this->message->getHeaders()
        );
    }

    public function testGetHeaderLine(): void
    {
        $value = 'value2';
        $message = $this->message->withAddedHeader(self::HEADER_NAME_1, $value);
        self::assertEquals(implode(',', [self::HEADER_VALUE_1, $value]), $message->getHeaderLine('name'));
        self::assertEquals(self::HEADER_VALUE_2, $message->getHeaderLine('test'));
    }

    public function testWithHeader(): void
    {
        $messageWithHeader = $this->message->withHeader('A', 'b');
        self::assertNotSame($this->message, $messageWithHeader);
        self::assertEquals(['b'], $messageWithHeader->getHeader('a'));

        self::assertEquals(['c'], $messageWithHeader->withHeader('a', 'c')->getHeader('a'));
    }

    public function testWithAddedHeader(): void
    {
        $value = [self::HEADER_VALUE_1, 'value2'];
        $messageWithAddedHeader = $this->message->withAddedHeader(self::HEADER_NAME_1, $value[1]);
        self::assertNotSame($this->message, $messageWithAddedHeader);
        self::assertEquals($value, $messageWithAddedHeader->getHeader(self::HEADER_NAME_1));
    }

    public function testWithoutHeader(): void
    {
        $messageWithoutHeader = $this->message->withoutHeader(strtoupper(self::HEADER_NAME_1));
        self::assertEmpty($messageWithoutHeader->getHeader(self::HEADER_NAME_1));
        self::assertNotSame($this->message, $messageWithoutHeader);
    }

    public function testProtocolVersion(): void
    {
        $version = 2.0;
        $messageWithProtocolVersion = $this->message->withProtocolVersion($version);
        self::assertEquals($version, $messageWithProtocolVersion->getProtocolVersion());
        self::assertNotSame($this->message, $messageWithProtocolVersion);
    }

    public function testBody(): void
    {
        $body = new Stream();
        $messageWithBody = $this->message->withBody($body);
        self::assertEquals($body, $messageWithBody->getBody());
        self::assertNotSame($this->message, $messageWithBody);
    }
}
