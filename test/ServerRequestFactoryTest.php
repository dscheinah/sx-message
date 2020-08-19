<?php
namespace Sx\MessageTest;

use Sx\Message\ServerRequestFactory;
use PHPUnit\Framework\TestCase;
use Sx\MessageTest\Mock\UriFactory;

class ServerRequestFactoryTest extends TestCase
{
    public function testCreateServerRequest(): void
    {
        $_GET = ['get' => 'get'];
        $_POST = ['post' => 'post'];
        $_COOKIE = ['cookie' => 'cookie'];

        $serverParams = [
            'server' => 'param',
            'HTTP_header' => 'line',
        ];
        $uri = 'https://host.tld/path';

        $uriFactory = new UriFactory();
        $factory = new ServerRequestFactory($uriFactory);

        $request = $factory->createServerRequest('POST', $uri, $serverParams);
        self::assertEquals('post', $request->getMethod());
        self::assertEquals($uri, $uriFactory->created);
        self::assertEquals(UriFactory::PATH, $request->getRequestTarget());
        self::assertEquals($serverParams, $request->getServerParams());
        self::assertEquals(['line'], $request->getHeader('header'));
        self::assertEquals($_GET, $request->getQueryParams());
        self::assertEquals($_POST, $request->getParsedBody());
        self::assertEquals('get', $request->getAttribute('get'));
        self::assertEquals('post', $request->getAttribute('post'));
        self::assertEquals($_COOKIE, $request->getCookieParams());
    }
}
