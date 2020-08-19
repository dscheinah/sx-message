<?php
namespace Sx\MessageTest\Container;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Sx\Container\Injector;
use Sx\Message\Container\MessageProvider;
use PHPUnit\Framework\TestCase;
use Sx\Message\Response\Json;
use Sx\Message\Response\ResponseHelperInterface;

class MessageProviderTest extends TestCase
{
    public function testProvide(): void
    {
        $injector = new Injector();
        $provider = new MessageProvider();
        $provider->provide($injector);
        self::assertTrue($injector->has(Json::class));
        self::assertTrue($injector->has(ResponseHelperInterface::class));
        self::assertTrue($injector->has(ResponseFactoryInterface::class));
        self::assertTrue($injector->has(StreamFactoryInterface::class));
    }
}
