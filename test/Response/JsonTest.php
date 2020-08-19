<?php
namespace Sx\MessageTest\Response;

use JsonException;
use PHPUnit\Framework\TestCase;
use Sx\Message\Response\Json;
use Sx\Message\ResponseFactory;
use Sx\Message\StreamFactory;

class JsonTest extends TestCase
{
    public function testCreate(): void
    {
        $json = new Json(new ResponseFactory(), new StreamFactory());
        try {
            $response = $json->create(204);
            self::assertEquals(204, $response->getStatusCode());
            self::assertEquals('""', (string) $response->getBody());

            $payload = ['test' => 1];
            $response = $json->create(200, $payload);
            self::assertEquals(200, $response->getStatusCode());
            self::assertEquals(json_encode($payload, JSON_THROW_ON_ERROR), (string) $response->getBody());
        } catch (JsonException $e) {
            self::assertFalse(true);
        }
        $this->expectException(JsonException::class);
        $json->create(500, fopen('php://temp', 'rb'));
    }
}
