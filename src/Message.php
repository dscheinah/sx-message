<?php
namespace Sx\Message;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

/**
 * The PSR message implementation used by Requests and Responses.
 */
class Message implements MessageInterface
{
    // This header is used in Request for withUri.
    public const HEADER_HOST = 'host';

    /**
     * The protocol version of the message. If implemented, a reasonable default should be set.
     *
     * @var string
     */
    protected string $version = '0';

    /**
     * The headers of the message collected as an array of values for each header.
     *
     * @var array<string, string[]>
     */
    protected array $headers = [];

    /**
     * The mapping of lowercase header names to the original names present in headers.
     *
     * @var string[]
     */
    protected array $mapper = [];

    /**
     * The message body as a stream.
     *
     * @var StreamInterface
     */
    protected StreamInterface $body;

    /**
     * Returns the current protocol version. This will only return 0 in all current implementations.
     *
     * @return string
     */
    public function getProtocolVersion(): string
    {
        return $this->version;
    }

    /**
     * Creates a new message instance with the set version.
     *
     * @param string $version
     *
     * @return MessageInterface
     */
    public function withProtocolVersion(string $version): MessageInterface
    {
        $message = clone $this;
        $message->version = $version;
        return $message;
    }

    /**
     * Returns all headers as a nested array of values. The first level contains the names as keys.
     *
     * @return string[][]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Checks if the header with the given name has been set.
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasHeader(string $name): bool
    {
        // Since headers are case-insensitive, the mapper is used to check the insensitive name.
        $name = strtolower($name);
        return isset($this->mapper[$name], $this->headers[$this->mapper[$name]]);
    }

    /**
     * Returns the value for the given name as an array of values.
     *
     * @param string $name
     *
     * @return string[]
     */
    public function getHeader(string $name) : array
    {
        // Use the lower name to represent the insensitive header.
        $name = strtolower($name);
        if (!$this->hasHeader($name)) {
            return [];
        }
        // With the previous check of hasHeader, the index must be accessible.
        return $this->headers[$this->mapper[$name]];
    }

    /**
     * Returns the header line imploded from the values of the given header.
     *
     * @param string $name
     *
     * @return string
     */
    public function getHeaderLine(string $name): string
    {
        return implode(',', $this->getHeader($name));
    }

    /**
     * Sets a new header on a new message instance. The name will be stored as is but accessible as an insensitive key.
     *
     * @param string          $name
     * @param string|string[] $value
     *
     * @return MessageInterface
     */
    public function withHeader(string $name, $value): MessageInterface
    {
        // Allow a string to be set, but ensure to store values as an array.
        if (!is_array($value)) {
            $value = [
                $value,
            ];
        }
        // Use the lower variant to store the mapping to the original name.
        // All other header functions will do the same when searching for a header given by name.
        $lowerName = strtolower($name);
        // If the header was already set, it needs to be unset with its original name. Just replacing the name in
        // the headers-array would not help if the names' cases do not match.
        if (isset($this->mapper[$lowerName])) {
            $message = $this->withoutHeader($lowerName);
            assert($message instanceof self);
        } else {
            // The call to withoutHeader already cloned the message. So only do it here.
            $message = clone $this;
        }
        // Set the header and the insensitive key.
        $message->mapper[$lowerName] = $name;
        $message->headers[$name] = $value;
        return $message;
    }

    /**
     * Appends a value to the header on a new message instance.
     *
     * @param string          $name
     * @param string|string[] $value
     *
     * @return MessageInterface
     */
    public function withAddedHeader(string $name, $value): MessageInterface
    {
        // Get the already set header.
        $header = $this->getHeader($name);
        // Append all new values to the header.
        if (!is_array($value)) {
            $value = [
                $value,
            ];
        }
        foreach ($value as $current) {
            $header[] = $current;
        }
        // Replace the header. Unset first to not duplicate the entry if called with differently cased names.
        $message = $this->withoutHeader($name);
        assert($message instanceof self);
        $message->mapper[strtolower($name)] = $name;
        $message->headers[$name] = $header;
        return $message;
    }

    /**
     * Removes a header from a new message instance.
     *
     * @param string $name
     *
     * @return MessageInterface
     */
    public function withoutHeader(string $name): MessageInterface
    {
        $message = clone $this;
        unset($message->mapper[strtolower($name)], $message->headers[$name]);
        return $message;
    }

    /**
     * Returns the currently set body as a stream if it was set before.
     *
     * @return StreamInterface
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    /**
     * Sets the body on a new message instance.
     *
     * @param StreamInterface $body
     *
     * @return MessageInterface
     */
    public function withBody(StreamInterface $body): MessageInterface
    {
        $message = clone $this;
        $message->body = $body;
        return $message;
    }
}
