<?php
namespace Sx\MessageTest;

use Sx\MessageTest\Mock\UriFactory;
use Sx\Message\RequestFactory;
use PHPUnit\Framework\TestCase;

class RequestFactoryTest extends TestCase
{
    public function testCreateRequest(): void
    {
        $uri = 'https://uri.tld/path';
        $uriFactory = new UriFactory();
        $factory = new RequestFactory($uriFactory);
        $request = $factory->createRequest('POST', $uri);
        self::assertEquals($uriFactory->created, $uri);
        self::assertEquals('post', $request->getMethod());
        self::assertEquals(UriFactory::PATH, $request->getRequestTarget());
    }
}
