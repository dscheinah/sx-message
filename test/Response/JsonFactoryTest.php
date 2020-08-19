<?php

namespace Sx\MessageTest\Response;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Sx\Container\Injector;
use Sx\Message\Response\Json;
use Sx\Message\Response\JsonFactory;
use PHPUnit\Framework\TestCase;
use Sx\Message\ResponseFactory;
use Sx\Message\StreamFactory;

class JsonFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $injector = new Injector();
        $injector->set(ResponseFactoryInterface::class, ResponseFactory::class);
        $injector->set(StreamFactoryInterface::class, StreamFactory::class);

        $factory = new JsonFactory();
        $factory->create($injector, [], Json::class);
        self::assertTrue(true);
    }
}
