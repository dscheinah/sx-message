<?php
namespace Sx\MessageTest;

use Sx\Message\ResponseFactory;
use PHPUnit\Framework\TestCase;

class ResponseFactoryTest extends TestCase
{
    public function testCreateResponse(): void
    {
        $factory = new ResponseFactory();
        $response = $factory->createResponse(204, 'test');
        self::assertEquals(204, $response->getStatusCode());
        self::assertEquals('test', $response->getReasonPhrase());
    }
}
