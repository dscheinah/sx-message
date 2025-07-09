<?php

namespace Sx\Message;

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use RuntimeException;

/**
 * Implements the functionality of one file upload.
 */
class UploadedFile implements UploadedFileInterface
{
    /**
     * The stream to use as the file.
     *
     * @var StreamInterface|null
     */
    protected ?StreamInterface $stream = null;

    /**
     * The size of the file.
     *
     * @var int|null
     */
    protected ?int $size = null;

    /**
     * The error code of the upload if any.
     *
     * @var int
     */
    protected int $error = 0;

    /**
     * The client's name of the file.
     *
     * @var string|null
     */
    protected ?string $clientFilename = null;

    /**
     * The client's media type of the file.
     *
     * @var string|null
     */
    protected ?string $clientMediaType;

    /**
     * Creates the uploaded file with all values.
     *
     * @param StreamInterface $stream
     * @param int|null $size
     * @param int $error
     * @param string|null $clientFilename
     * @param string|null $clientMediaType
     */
    public function __construct(
        StreamInterface $stream,
        int $size = null,
        int $error = UPLOAD_ERR_OK,
        string $clientFilename = null,
        string $clientMediaType = null
    ) {
        $this->stream = $stream;
        $this->size = $size;
        $this->error = $error;
        $this->clientFilename = $clientFilename;
        $this->clientMediaType = $clientMediaType;
    }

    /**
     * Returns a stream representing the uploaded file if available, and moveTo was not called before.
     *
     * @return StreamInterface
     * @throws RuntimeException
     */
    public function getStream(): StreamInterface
    {
        if (!$this->stream) {
            throw new RuntimeException('no stream is available');
        }
        return $this->stream;
    }

    /**
     * Moves the uploaded file to a new location if available and not yet moved.
     *
     * @param string $targetPath
     *
     * @return void
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function moveTo(string $targetPath): void
    {
        if (!$this->stream) {
            throw new RuntimeException('no stream is available');
        }
        if (!@move_uploaded_file($this->stream->getMetadata('uri') ?: '', $targetPath)) {
            throw new InvalidArgumentException('could not move the uploaded file');
        }
        $this->stream->close();
        $this->stream = null;
    }

    /**
     * Returns the file size if available.
     *
     * @return int|null
     */
    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     * Returns the error associated with the uploaded file as one of the UPLOAD_ERR_XXX constants.
     *
     * @return int
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * Returns the filename sent by the client if available.
     *
     * @return string|null
     */
    public function getClientFilename(): ?string
    {
        return $this->clientFilename;
    }

    /**
     * Returns the media type sent by the client if available.
     *
     * @return string|null
     */
    public function getClientMediaType(): ?string
    {
        return $this->clientMediaType;
    }
}
