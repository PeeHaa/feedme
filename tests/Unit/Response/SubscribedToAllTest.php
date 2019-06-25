<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Response;

use PeeHaa\FeedMe\Response\SubscribedToAll;
use PHPUnit\Framework\TestCase;

class SubscribedToAllTest extends TestCase
{
    /** @var SubscribedToAll */
    private $response;

    public function setUp(): void
    {
        $this->response = new SubscribedToAll('requestId');
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

        $this->assertSame('requestId', $responseData['requestId']);
        $this->assertSame(200, $responseData['status']);
    }
}
