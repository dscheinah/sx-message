<?php
namespace Sx\MessageTest;

use Sx\Message\Uri;
use PHPUnit\Framework\TestCase;

class UriTest extends TestCase
{
    private $uri;

    protected function setUp(): void
    {
        $this->uri = new Uri();
    }

    public function testFragment(): void
    {
        $fragment = 'test';
        $uriWithFragment = $this->uri->withFragment($fragment);
        self::assertEquals($fragment, $uriWithFragment->getFragment());
        self::assertNotSame($this->uri, $uriWithFragment);
    }

    public function testHost(): void
    {
        $host = 'host.tld';
        $uriWithHost = $this->uri->withHost($host);
        self::assertEquals($host, $uriWithHost->getHost());
        self::assertNotSame($this->uri, $uriWithHost);
    }

    public function testPort(): void
    {
        $port = 443;
        $uriWithPort = $this->uri->withPort($port);
        self::assertEquals($port, $uriWithPort->getPort());
        self::assertNotSame($this->uri, $uriWithPort);
        self::assertNull($uriWithPort->withScheme(Uri::SCHEME_HTTPS)->getPort());
    }

    public function testUserInfo(): void
    {
        $user = 'user';
        $password = 'password';
        $uriWithUserInfo = $this->uri->withUserInfo($user, $password);
        self::assertEquals("$user:$password", $uriWithUserInfo->getUserInfo());
        self::assertNotSame($this->uri, $uriWithUserInfo);
    }

    public function testScheme(): void
    {
        $uriWithScheme = $this->uri->withScheme(Uri::SCHEME_FTP);
        self::assertEquals(Uri::SCHEME_FTP, $uriWithScheme->getScheme());
        self::assertNotSame($this->uri, $uriWithScheme);

        self::assertEquals('test', $this->uri->withScheme('TEST')->getScheme());
    }

    public function testQuery(): void
    {
        $query = 'test=value&key';
        $uriWithQuery = $this->uri->withQuery($query);
        self::assertEquals($query, $uriWithQuery->getQuery());
        self::assertNotSame($this->uri, $uriWithQuery);

        $uriWithQuery = $this->uri->withQuery('?' . $query . '?');
        self::assertEquals($query, $uriWithQuery->getQuery());
    }

    public function testPath(): void
    {
        $path = 'path';
        $uriWithPath = $this->uri->withPath($path);
        self::assertEquals($path, $uriWithPath->getPath());
        self::assertNotSame($this->uri, $uriWithPath);
    }

    public function testToString(): void
    {
        $uri = $this->uri;
        self::assertEquals('', (string) $uri);
        $uri = $uri->withPath('path/to/access');
        self::assertEquals('path/to/access', (string) $uri);
        $uri = $uri->withHost('host.tld');
        self::assertEquals('//host.tld/path/to/access', (string) $uri);
        $uri = $uri->withPath('//path/to/access');
        self::assertEquals('//host.tld/path/to/access', (string) $uri);
        $uri = $uri->withScheme(Uri::SCHEME_HTTPS)->withPort(443);
        self::assertEquals('https://host.tld/path/to/access', (string) $uri);
        $uri = $uri->withPort('8443');
        self::assertEquals('https://host.tld:8443/path/to/access', (string) $uri);
        $uri = $uri->withUserInfo('user');
        self::assertEquals('https://user@host.tld:8443/path/to/access', (string) $uri);
        $uri = $uri->withUserInfo('user', 'password');
        self::assertEquals('https://user:password@host.tld:8443/path/to/access', (string) $uri);
        $uri = $uri->withQuery('k=v&t');
        self::assertEquals('https://user:password@host.tld:8443/path/to/access?k=v&t', (string) $uri);
        $uri = $uri->withFragment('fragment');
        self::assertEquals('https://user:password@host.tld:8443/path/to/access?k=v&t#fragment', (string) $uri);
    }
}
