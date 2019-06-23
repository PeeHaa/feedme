<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Response;

use PeeHaa\FeedMe\Response\Error;
use PHPUnit\Framework\TestCase;

class ErrorTest extends TestCase
{
    public function testToJsonReturnsCorrectKeys(): void
    {
        $responseData = json_decode((new Error('TheId', ['username' => 'wrong']))->toJson(), true);

        $this->assertArrayHasKey('requestId', $responseData);
        $this->assertArrayHasKey('status', $responseData);
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey('username', $responseData['errors']);
    }

    public function testToJsonReturnsFormattedData(): void
    {
        $responseData = json_decode((new Error('TheId', ['username' => 'wrong']))->toJson(), true);

        $this->assertSame('TheId', $responseData['requestId']);
        $this->assertSame(401, $responseData['status']);
        $this->assertSame(['username' => 'wrong'], $responseData['errors']);
    }
}
