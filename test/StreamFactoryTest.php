<?php
namespace Sx\MessageTest;

use Sx\Message\StreamFactory;
use PHPUnit\Framework\TestCase;

class StreamFactoryTest extends TestCase
{
    private const FILE = __DIR__ . '/assets/stream.txt';

    private $factory;

    protected function setUp(): void
    {
        $this->factory = new StreamFactory();
    }

    public function testCreate(): void
    {
        $empty = $this->factory->createStream();
        self::assertEmpty((string) $empty);

        $content = 'content';
        $notEmpty = $this->factory->createStream($content);
        self::assertEquals($content, (string) $notEmpty);

        $stream = $this->factory->createStreamFromFile(self::FILE);
        self::assertStringEqualsFile(self::FILE, (string) $stream);

        self::assertTrue($this->factory->createStreamFromFile('')->isReadable());
    }
}
