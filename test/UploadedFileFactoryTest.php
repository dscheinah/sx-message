<?php

namespace Sx\MessageTest;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Sx\Message\UploadedFileFactory;

class UploadedFileFactoryTest extends TestCase
{
    private UploadedFileFactory $factory;

    private MockObject $streamMock;

    protected function setUp(): void
    {
        $this->streamMock = $this->createMock(StreamInterface::class);
        $this->factory = new UploadedFileFactory();
    }

    public function testCreateUploadedFile(): void
    {
        $this->expectNotToPerformAssertions();
        $this->factory->createUploadedFile($this->streamMock, 42, UPLOAD_ERR_INI_SIZE, 'test', 'test');
    }

    public function testCreateUploadedFileWithMinimalArguments(): void
    {
        $this->streamMock->method('getSize')->willReturn(23);
        $uploadedFile = $this->factory->createUploadedFile($this->streamMock);
        self::assertEquals(23, $uploadedFile->getSize());
        self::assertEquals(UPLOAD_ERR_OK, $uploadedFile->getError());
        self::assertNull($uploadedFile->getClientFilename());
        self::assertNull($uploadedFile->getClientMediaType());
    }
}
