<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Entity;

use PeeHaa\FeedMe\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /** @var User */
    private $user;

    public function setUp(): void
    {
        $this->user = new User('UserId', 'UserEmail', 'UserHash');
    }

    public function testGetId(): void
    {
        $this->assertSame('UserId', $this->user->getId());
    }

    public function testGetUsername(): void
    {
        $this->assertSame('UserEmail', $this->user->getUsername());
    }

    public function testGetHash(): void
    {
        $this->assertSame('UserHash', $this->user->getHash());
    }
}
