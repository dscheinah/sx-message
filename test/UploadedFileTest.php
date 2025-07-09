<?php

namespace Sx\MessageTest {

    use InvalidArgumentException;
    use PHPUnit\Framework\MockObject\MockObject;
    use Psr\Http\Message\StreamInterface;
    use RuntimeException;
    use Sx\Message\UploadedFile;
    use PHPUnit\Framework\TestCase;

    class UploadedFileTest extends TestCase
    {
        private const SIZE = 42;
        private const ERROR = UPLOAD_ERR_PARTIAL;
        private const NAME = 'test-name';
        private const TYPE = 'test-type';

        private UploadedFile $uploadedFile;

        private MockObject $streamMock;

        protected function setUp(): void
        {
            $this->streamMock = $this->createMock(StreamInterface::class);
            $this->uploadedFile = new UploadedFile(
                $this->streamMock,
                self::SIZE,
                self::ERROR,
                self::NAME,
                self::TYPE,
            );
        }

        public function testGetStream(): void
        {
            self::assertSame($this->streamMock, $this->uploadedFile->getStream());
            $this->expectException(RuntimeException::class);
            $this->uploadedFile->moveTo('success');
            $this->uploadedFile->getStream();
        }

        public function testMoveTo(): void
        {
            $this->streamMock
                ->expects(self::once())
                ->method('getMetadata')
                ->with('uri')
                ->willReturn('success');
            $this->uploadedFile->moveTo('any');
        }

        public function testMoveToNoStream(): void
        {
            $this->expectException(RuntimeException::class);
            $this->streamMock
                ->expects(self::once())
                ->method('getMetadata')
                ->with('uri')
                ->willReturn('success');
            $this->uploadedFile->moveTo('any');
            $this->uploadedFile->moveTo('success');
        }

        public function testMoveToError(): void
        {
            $this->expectException(InvalidArgumentException::class);
            $this->streamMock
                ->expects(self::once())
                ->method('getMetadata')
                ->with('uri')
                ->willReturn('success');
            $this->uploadedFile->moveTo('error');
        }

        public function testGetSize(): void
        {
            self::assertEquals(self::SIZE, $this->uploadedFile->getSize());
        }

        public function testGetError(): void
        {
            self::assertEquals(self::ERROR, $this->uploadedFile->getError());
        }

        public function testGetClientFilename(): void
        {
            self::assertEquals(self::NAME, $this->uploadedFile->getClientFilename());
        }

        public function testGetClientMediaType(): void
        {
            self::assertEquals(self::TYPE, $this->uploadedFile->getClientMediaType());
        }
    }
}

namespace Sx\Message {
    function move_uploaded_file($source, $targetPath): bool
    {
        switch ($targetPath) {
            case 'success':
                return true;
            case 'error':
                return false;
            default:
                return $source === 'success';
        }
    }
}
