<?php
namespace Sx\MessageTest;

use RuntimeException;
use Sx\Message\Stream;
use PHPUnit\Framework\TestCase;

class StreamTest extends TestCase
{
    private const FILE = __DIR__ . '/assets/stream.txt';

    private $stream;

    private $resource;

    protected function setUp(): void
    {
        $this->resource = fopen(self::FILE, 'rb');
        $this->stream = new Stream($this->resource);
    }

    public function testGetContents(): void
    {
        self::assertStringEqualsFile(self::FILE, $this->stream->getContents());
        self::assertEmpty((string) new Stream());
    }

    public function testToString(): void
    {
        self::assertStringEqualsFile(self::FILE, (string) $this->stream);
        self::assertEmpty((string) new Stream());
    }

    public function testDetach(): void
    {
        $resource = $this->stream->detach();
        self::assertSame($this->resource, $resource);
        self::assertEmpty($this->stream->getContents());
    }

    public function testClose(): void
    {
        $this->stream->close();
        self::assertIsClosedResource($this->resource);
    }

    public function testTell(): void
    {
        self::assertEquals(ftell($this->resource), $this->stream->tell());
        $this->expectException(RuntimeException::class);
        (new Stream())->tell();
    }

    public function testEof(): void
    {
        self::assertFalse($this->stream->eof());
        $this->stream->read(1024);
        self::assertTrue($this->stream->eof());
        self::assertTrue((new Stream())->eof());
    }

    public function testGetSize(): void
    {
        self::assertEquals(strlen(file_get_contents(self::FILE)), $this->stream->getSize());
        self::assertNull((new Stream())->getSize());
    }

    public function testGetMetadata(): void
    {
        $metadata = $this->stream->getMetadata();
        self::assertEquals(stream_get_meta_data($this->resource), $metadata);
        foreach ($metadata as $key => $value) {
            self::assertEquals($value, $this->stream->getMetadata($key));
        }
        self::assertNull((new Stream())->getMetadata());
    }

    public function testIsWritable(): void
    {
        self::assertFalse($this->stream->isWritable());
        self::assertFalse((new Stream())->isWritable());
        $stream = new Stream(fopen('/tmp/stream.txt', 'wb'));
        self::assertTrue($stream->isWritable());
    }

    public function testWrite(): void
    {
        $string = 'test';

        $file = sys_get_temp_dir() . '/stream.txt';
        $stream = new Stream(fopen($file, 'wb'));
        self::assertEquals(strlen($string), $stream->write($string));
        self::assertStringEqualsFile($file, $string);

        self::assertEquals(0, (new Stream())->write('test'));

        $this->expectException(RuntimeException::class);
        $this->stream->write($string);

        @unlink($file);
    }

    public function testIsReadable(): void
    {
        self::assertTrue($this->stream->isReadable());
        $file = '/tmp/stream.txt';
        $stream = new Stream(fopen($file, 'wb'));
        self::assertFalse($stream->isReadable());
        self::assertFalse((new Stream())->isReadable());
    }

    public function testRead(): void
    {
        self::assertEquals('Line1', $this->stream->read(5));
        self::assertEquals("\nLine2\nLine3\n", $this->stream->read(128));
        self::assertEmpty($this->stream->read(128));
        $this->expectException(RuntimeException::class);
        (new Stream())->read(1024);
    }

    public function testSeek(): void
    {
        $this->stream->seek(5);
        self::assertEquals(ftell($this->resource), $this->stream->tell());
        $this->stream->seek(5, SEEK_CUR);
        self::assertEquals(ftell($this->resource), $this->stream->tell());
        $this->expectException(RuntimeException::class);
        (new Stream())->seek(1024);
    }

    public function testIsSeekable(): void
    {
        self::assertTrue($this->stream->isSeekable());
        self::assertFalse((new Stream())->isSeekable());
    }

    public function testRewind(): void
    {
        $this->stream->rewind();
        $this->expectException(RuntimeException::class);
        (new Stream())->rewind();
    }
}
