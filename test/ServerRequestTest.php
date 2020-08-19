<?php
namespace Sx\MessageTest;

use Sx\Message\ServerRequest;
use PHPUnit\Framework\TestCase;

class ServerRequestTest extends TestCase
{
    private const SERVER_PARAMS = ['key' => 'value'];
    private const KEY = 'key';
    private const VALUE = 'value';

    private $request;

    protected function setUp(): void
    {
        $this->request = (new ServerRequest(self::SERVER_PARAMS))
            ->withAttribute(self::KEY, self::VALUE);
    }

    public function testGetServerParams(): void
    {
        self::assertEquals(self::SERVER_PARAMS, $this->request->getServerParams());
    }

    public function testCookieParams(): void
    {
        $cookies = ['cookie' => 'mmh'];
        $requestWithCookieParams = $this->request->withCookieParams($cookies);
        self::assertEquals($cookies, $requestWithCookieParams->getCookieParams());
        self::assertNotSame($this->request, $requestWithCookieParams);
    }

    public function testParsedBody(): void
    {
        $body = ['body'];
        $requestWithParsedBody = $this->request->withParsedBody($body);
        self::assertEquals($body, $requestWithParsedBody->getParsedBody());
        self::assertNotSame($this->request, $requestWithParsedBody);
    }

    public function testUploadedFiles(): void
    {
        $uploads = ['file'];
        $requestWithUploadedFiles = $this->request->withUploadedFiles($uploads);
        self::assertEquals($uploads, $requestWithUploadedFiles->getUploadedFiles());
        self::assertNotSame($this->request, $requestWithUploadedFiles);
    }

    public function testQueryParams(): void
    {
        $params = ['query' => 'value'];
        $requestWithQueryParams = $this->request->withQueryParams($params);
        self::assertEquals($params, $requestWithQueryParams->getQueryParams());
        self::assertNotSame($this->request, $requestWithQueryParams);
    }

    public function testGetAttribute(): void
    {
        self::assertEquals(self::VALUE, $this->request->getAttribute(self::KEY));
        self::assertEquals('test', $this->request->getAttribute('default', 'test'));
    }

    public function testWithoutAttribute(): void
    {
        $requestWithoutAttribute = $this->request->withoutAttribute(self::KEY);
        self::assertNull($requestWithoutAttribute->getAttribute(self::KEY));
        self::assertNotSame($this->request, $requestWithoutAttribute);
    }

    public function testWithAttribute(): void
    {
        $requestWithAttribute = $this->request->withAttribute('test', 'test');
        self::assertEquals('test', $requestWithAttribute->getAttribute('test'));
        self::assertNotSame($this->request, $requestWithAttribute);
    }

    public function testGetAttributes(): void
    {
        self::assertEquals([self::KEY => self::VALUE], $this->request->getAttributes());
    }
}
