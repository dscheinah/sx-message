<?php

namespace Sx\Message;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * A PSR compatible factory to create a file upload. It is not meant to be used as a factory for the Injector.
 */
class UploadedFileFactory implements UploadedFileFactoryInterface
{
    /**
     * Creates an uploaded file.
     *
     * @param StreamInterface $stream
     * @param int|null $size
     * @param int $error
     * @param string|null $clientFilename
     * @param string|null $clientMediaType
     *
     * @return UploadedFileInterface
     */
    public function createUploadedFile(
        StreamInterface $stream,
        ?int $size = null,
        int $error = UPLOAD_ERR_OK,
        ?string $clientFilename = null,
        ?string $clientMediaType = null
    ): UploadedFileInterface {
        return new UploadedFile(
            $stream,
            $size ?: $stream->getSize(),
            $error,
            $clientFilename,
            $clientMediaType,
        );
    }
}
