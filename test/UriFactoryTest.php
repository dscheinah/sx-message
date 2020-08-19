<?php
namespace Sx\MessageTest;

use Sx\Message\UriFactory;
use PHPUnit\Framework\TestCase;

class UriFactoryTest extends TestCase
{
    public function testCreateUri(): void
    {
        $uriString = 'https://user:password@host.tld:8443/path/to/access?k=v&t#fragment';
        $factory = new UriFactory();
        $uri = $factory->createUri($uriString);
        self::assertEquals($uriString, (string) $uri);
    }
}
