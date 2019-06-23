<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Response;

use PeeHaa\FeedMe\Response\LogInInvalid;
use PHPUnit\Framework\TestCase;

class LogInInvalidTest extends TestCase
{
    public function testToJsonReturnsCorrectKeys(): void
    {
        $responseData = json_decode((new LogInInvalid('TheId'))->toJson(), true);

        $this->assertArrayHasKey('requestId', $responseData);
        $this->assertArrayHasKey('status', $responseData);
    }

    public function testToJsonReturnsFormattedData(): void
    {
        $responseData = json_decode((new LogInInvalid('TheId'))->toJson(), true);

        $this->assertSame('TheId', $responseData['requestId']);
        $this->assertSame(401, $responseData['status']);
    }
}
