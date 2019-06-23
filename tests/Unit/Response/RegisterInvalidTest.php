<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Response;

use PeeHaa\FeedMe\Response\RegisterInvalid;
use PHPUnit\Framework\TestCase;

class RegisterInvalidTest extends TestCase
{
    public function testToJsonReturnsCorrectKeys(): void
    {
        $responseData = json_decode((new RegisterInvalid('TheId', ['username' => 'wrong']))->toJson(), true);

        $this->assertArrayHasKey('requestId', $responseData);
        $this->assertArrayHasKey('status', $responseData);
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey('username', $responseData['errors']);
    }

    public function testToJsonReturnsFormattedData(): void
    {
        $responseData = json_decode((new RegisterInvalid('TheId', ['username' => 'wrong']))->toJson(), true);

        $this->assertSame('TheId', $responseData['requestId']);
        $this->assertSame(406, $responseData['status']);
        $this->assertSame(['username' => 'wrong'], $responseData['errors']);
    }
}
