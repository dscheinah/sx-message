<?php
namespace Sx\MessageTest\Mock;

use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Sx\Message\Uri;

class UriFactory implements UriFactoryInterface
{
    public const PATH = 'path';

    public $created;

    public function createUri(string $uri = ''): UriInterface
    {
        $this->created = $uri;
        return (new Uri())->withPath(self::PATH);
    }
}
