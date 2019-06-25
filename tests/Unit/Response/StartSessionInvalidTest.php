<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Response;

use PeeHaa\FeedMe\Response\StartSessionInvalid;
use PHPUnit\Framework\TestCase;

class StartSessionInvalidTest extends TestCase
{
    /** @var StartSessionInvalid */
    private $response;

    public function setUp(): void
    {
        $this->response = new StartSessionInvalid();
    }

    public function testToJsonReturnsCorrectKeys(): void
    {
        $responseData = json_decode($this->response->toJson(), true);

        $this->assertArrayHasKey('requestId', $responseData);
        $this->assertArrayHasKey('status', $responseData);
    }

    public function testToJsonReturnsFormattedData(): void
    {
        $responseData = json_decode($this->response->toJson(), true);

        $this->assertSame('StartSession', $responseData['requestId']);
        $this->assertSame(401, $responseData['status']);
    }
}
