<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Entity;

use PeeHaa\FeedMe\Entity\NewUser;
use PHPUnit\Framework\TestCase;

class NewUserTest extends TestCase
{
    /** @var NewUser */
    private $newUser;

    public function setUp(): void
    {
        $this->newUser = new NewUser('UserId', 'UserEmail', 'UserPassword');
    }

    public function testGetId(): void
    {
        $this->assertSame('UserId', $this->newUser->getId());
    }

    public function testGetUsername(): void
    {
        $this->assertSame('UserEmail', $this->newUser->getUsername());
    }

    public function testGetPassword(): void
    {
        $this->assertSame('UserPassword', $this->newUser->getPassword());
    }
}
