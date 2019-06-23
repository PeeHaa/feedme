<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Response;

use PeeHaa\FeedMe\Entity\Session;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Response\LogInValid;
use PHPUnit\Framework\TestCase;

class LogInValidTest extends TestCase
{
    /** @var LogInValid */
    private $response;

    public function setUp(): void
    {
        $this->response = new LogInValid(
            'TheId',
            new User('id', 'username', 'dsjdskljskjd'),
            new Session('id', 1, 'userId', 'token', new \DateTimeImmutable()),
        );
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

        $this->assertSame('TheId', $responseData['requestId']);
        $this->assertSame(200, $responseData['status']);
    }
}
