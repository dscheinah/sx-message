<?php
namespace Sx\MessageTest;

use Sx\Message\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    private const CODE = 204;
    private const REASON = 'test';

    private $response;

    protected function setUp(): void
    {
        $this->response = (new Response())->withStatus(self::CODE, self::REASON);
    }

    public function testGetReasonPhrase(): void
    {
        self::assertEquals(self::REASON, $this->response->getReasonPhrase());
    }

    public function testGetStatusCode(): void
    {
        self::assertEquals(self::CODE, $this->response->getStatusCode());
    }

    public function testWithStatus(): void
    {
        $responseWithStatus = $this->response->withStatus(404, 'reason');
        self::assertNotSame($this->response, $responseWithStatus);
        self::assertEquals(404, $responseWithStatus->getStatusCode());
        self::assertEquals('reason', $responseWithStatus->getReasonPhrase());
    }
}
