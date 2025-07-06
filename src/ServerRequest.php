<?php
namespace Sx\Message;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Implements the functionality of all requests send to the server to be handled by middleware.
 */
class ServerRequest extends Request implements ServerRequestInterface
{
    /**
     * Hold all given server parameters including data not set by the client directly.
     *
     * @var array<string, mixed>
     */
    protected array $serverParams = [];

    /**
     * All cookies sent by the client.
     *
     * @var array<string, string>
     */
    protected array $cookieParams = [];

    /**
     * All query parameters (GET).
     *
     * @var array<string, mixed>
     */
    protected array $queryParams = [];

    /**
     * All uploaded files.
     *
     * @var UploadedFileInterface[]
     */
    protected array $uploads = [];

    /**
     * The parsed request body according to the content type of the request.
     *
     * @var array<mixed>|object|null
     */
    protected $parsedBody;

    /**
     * Contains the combined GET and POST parameters added by custom attributes from middleware.
     *
     * @var array<string, mixed>
     */
    protected array $attributes = [];

    /**
     * Creates a new server request with fixed server params.
     *
     * @param array<string, mixed> $serverParams
     */
    public function __construct(array $serverParams = [])
    {
        $this->serverParams = $serverParams;
    }

    /**
     * Returns the server params given to the constructor.
     *
     * @return array<string, mixed>
     */
    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    /**
     * Returns all cookies.
     *
     * @return array<string, string>
     */
    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    /**
     * Sets new cookies on a new server request instance.
     *
     * @param array<string, string> $cookies
     *
     * @return ServerRequestInterface
     */
    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        $request = clone $this;
        $request->cookieParams = $cookies;
        return $request;
    }

    /**
     * Get all query (GET) parameters.
     *
     * @return array<string, mixed>
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * Sets new query parameters on a new server request instance. Attributes will be kept unchanged.
     *
     * @param array<string, mixed> $query
     *
     * @return ServerRequestInterface
     */
    public function withQueryParams(array $query): ServerRequestInterface
    {
        $request = clone $this;
        $request->queryParams = $query;
        return $request;
    }

    /**
     * Returns all uploaded files.
     *
     * @return UploadedFileInterface[]
     */
    public function getUploadedFiles(): array
    {
        return $this->uploads;
    }

    /**
     * Sets new uploaded files on a new server request instance.
     *
     * @param UploadedFileInterface[] $uploadedFiles
     *
     * @return ServerRequestInterface
     */
    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        $request = clone $this;
        $request->uploads = $uploadedFiles;
        return $request;
    }

    /**
     * Returns the parsed body.
     *
     * @return array<mixed>|object|null
     */
    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    /**
     * Sets a new parsed body on a new server request instance. Attributes will be kept unchanged.
     *
     * @param array<mixed>|object|null $data
     *
     * @return ServerRequestInterface
     */
    public function withParsedBody($data): ServerRequestInterface
    {
        $request = clone $this;
        $request->parsedBody = $data;
        return $request;
    }

    /**
     * Returns all attributes set for the request.
     *
     * @return array<string, mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Returns the value for exactly one attribute. If the attribute was not set, the default is returned.
     * The default is also return if the set attribute falsifies.
     *
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed|null
     */
    public function getAttribute(string $name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * Sets a new attribute on a new server request instance.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return ServerRequestInterface
     */
    public function withAttribute(string $name, $value): ServerRequestInterface
    {
        $request = clone $this;
        $request->attributes[$name] = $value;
        return $request;
    }

    /**
     * Removes an attribute from a new server request instance.
     *
     * @param string $name
     *
     * @return ServerRequestInterface
     */
    public function withoutAttribute(string $name): ServerRequestInterface
    {
        $request = clone $this;
        unset($request->attributes[$name]);
        return $request;
    }
}
