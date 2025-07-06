<?php
namespace Sx\Message\Response;

use Sx\Container\FactoryInterface;
use Sx\Container\Injector;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * The factory to create the JSON helper. This is needed to get the PSR factories from the Injector.
 */
class JsonFactory implements FactoryInterface
{
    /**
     * Creates a new JSON helper with a response and stream factory.
     *
     * @param Injector $injector
     * @param array<mixed> $options
     * @param string $class
     *
     * @return Json
     */
    public function create(Injector $injector, array $options, string $class): Json
    {
        $responseFactory = $injector->get(ResponseFactoryInterface::class);
        assert($responseFactory instanceof ResponseFactoryInterface);
        $streamFactory = $injector->get(StreamFactoryInterface::class);
        assert($streamFactory instanceof StreamFactoryInterface);
        return new Json($responseFactory, $streamFactory);
    }
}
