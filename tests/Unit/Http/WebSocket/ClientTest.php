<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Http\WebSocket;

use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Http\WebSocket\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testGetId(): void
    {
        $this->assertSame(12, (new Client(12))->getId());
    }

    public function getGetUserReturnsNullWhenNotLoggedIn(): void
    {
        $this->assertNull((new Client(12))->getUser());
    }

    public function testGetUser(): void
    {
        $client = new Client(12, new User('id', 'username', 'hash'));

        $this->assertInstanceOf(User::class, $client->getUser());
    }
}
