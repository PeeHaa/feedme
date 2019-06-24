<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Entity;

use PeeHaa\FeedMe\Entity\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    /** @var Session */
    private $session;

    public function setUp(): void
    {
        $this->session = new Session('id', 1, 'userId', 'token', new \DateTimeImmutable());
    }

    public function testGetId(): void
    {
        $this->assertSame('id', $this->session->getId());
    }

    public function testGetClientId(): void
    {
        $this->assertSame(1, $this->session->getClientId());
    }

    public function testGetUserId(): void
    {
        $this->assertSame('userId', $this->session->getUserId());
    }

    public function testGetToken(): void
    {
        $this->assertSame('token', $this->session->getToken());
    }

    public function testGetExpiration(): void
    {
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->session->getExpiration());
    }
}
