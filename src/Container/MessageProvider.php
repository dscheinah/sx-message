<?php
namespace Sx\Message\Container;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Sx\Container\Injector;
use Sx\Container\ProviderInterface;
use Sx\Message\Response\Json;
use Sx\Message\Response\JsonFactory;
use Sx\Message\Response\ResponseHelperInterface;
use Sx\Message\ResponseFactory;
use Sx\Message\StreamFactory;

/**
 * This class registers the default factories for dependency injection. Use it with setup of the injector.
 */
class MessageProvider implements ProviderInterface
{
    /**
     * Registers the default factories.
     *
     * @param Injector $injector
     */
    public function provide(Injector $injector): void
    {
        $injector->set(ResponseFactoryInterface::class, ResponseFactory::class);
        $injector->set(StreamFactoryInterface::class, StreamFactory::class);
        $injector->set(Json::class, JsonFactory::class);
        // As there is currently only one helper implementation this default can be assumed.
        $injector->set(ResponseHelperInterface::class, JsonFactory::class);
    }
}
