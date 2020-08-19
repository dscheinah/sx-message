<?php
namespace Sx\Message\Container;

use Sx\Container\Injector;
use Sx\Container\ProviderInterface;
use Sx\Message\Response\Json;
use Sx\Message\Response\JsonFactory;
use Sx\Message\Response\ResponseHelperInterface;

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
        $injector->set(Json::class, JsonFactory::class);
        // As there is currently only one helper implementation this default can be assumed.
        $injector->set(ResponseHelperInterface::class, JsonFactory::class);
    }
}
