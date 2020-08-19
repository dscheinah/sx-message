<?php
namespace Sx\MessageTest;

use Sx\Message\Request;
use PHPUnit\Framework\TestCase;
use Sx\Message\Uri;

class RequestTest extends TestCase
{
    private $request;

    protected function setUp(): void
    {
        $this->request = new Request();
    }

    public function testMethod(): void
    {
        $requestWithMethod = $this->request->withMethod('GET');
        self::assertEquals('get', $requestWithMethod->getMethod());
        self::assertNotSame($this->request, $requestWithMethod);
    }

    public function testUri(): void
    {
        $uri = (new Uri())->withHost('host.tld');
        $requestWithUri = $this->request->withUri($uri, true);
        self::assertEquals($uri, $requestWithUri->getUri());
        self::assertNotSame($this->request, $requestWithUri);
        self::assertEquals([$uri->getHost()], $requestWithUri->getHeader(Request::HEADER_HOST));

        $nextUri = $uri->withHost('host2.tld');
        $nextRequest = $requestWithUri->withUri($nextUri);
        self::assertEquals([$nextUri->getHost()], $nextRequest->getHeader(Request::HEADER_HOST));

        $host = 'host3.tld';
        $hostRequest = $requestWithUri->withHeader(Request::HEADER_HOST, $host)->withUri($uri, true);
        self::assertEquals([$host], $hostRequest->getHeader(Request::HEADER_HOST));
    }

    public function testRequestTarget(): void
    {
        $target = 'target';
        $requestWithRequestTarget = $this->request->withRequestTarget($target);
        self::assertEquals($target, $requestWithRequestTarget->getRequestTarget());
        self::assertNotSame($this->request, $requestWithRequestTarget);
    }
}
