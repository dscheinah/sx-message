<?php
namespace Sx\Message;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * Implements the functionality of all requests.
 */
class Request extends Message implements RequestInterface
{
    /**
     * The target of the request.
     *
     * @var string
     */
    protected string $target = '';

    /**
     * The method of the request.
     *
     * @var string
     */
    protected string $method = '';

    /**
     * The URI of the request.
     *
     * @var UriInterface
     */
    protected UriInterface $uri;

    /**
     * Returns the current target.
     *
     * @return string
     */
    public function getRequestTarget(): string
    {
        return $this->target;
    }

    /**
     * Sets the target on a new request instance.
     *
     * @param string $requestTarget
     *
     * @return RequestInterface
     */
    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        $request = clone $this;
        $request->target = $requestTarget;
        return $request;
    }

    /**
     * Returns the method in lower case.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Sets the method on a new request instance. It will always be converted to lower case.
     *
     * @param string $method
     *
     * @return RequestInterface
     */
    public function withMethod(string $method): RequestInterface
    {
        $request = clone $this;
        $request->method = strtolower($method);
        return $request;
    }

    /**
     * Returns the URI of the request.
     *
     * @return UriInterface
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * Sets the URI on a new request instance. The host header will be used from the URIs host if preserveHost ist false.
     *
     * @param UriInterface $uri
     * @param bool         $preserveHost
     *
     * @return RequestInterface
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $request = null;
        $host = $uri->getHost();
        if (!$preserveHost) {
            // If the host shall not be preserved, it is replaced from the URI if available.
            if ($host) {
                $request = $this->withHeader(self::HEADER_HOST, $host);
                assert($request instanceof self);
            }
        } elseif ($host && !$this->getHeader(self::HEADER_HOST)) {
            // If the request does not have a host header, the URIs host will also be applied.
            $request = $this->withHeader(self::HEADER_HOST, $host);
            assert($request instanceof self);
        }
        // Since the withHeader function above already cloned the request do it only if needed.
        if (!$request) {
            $request = clone $this;
        }
        $request->uri = $uri;
        return $request;
    }
}
