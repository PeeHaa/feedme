<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Http\WebSocket;

use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Http\WebSocket\Clients;
use PHPUnit\Framework\TestCase;

class ClientsTest extends TestCase
{
    public function testAdd(): void
    {
        $clients = new Clients();

        $clients->add(12);

        $this->assertInstanceOf(Client::class, $clients->getById(12));
    }

    public function testRemoveById(): void
    {
        $clients = new Clients();

        $clients->add(12);

        $this->assertInstanceOf(Client::class, $clients->getById(12));

        $clients->removeById(12);

        $this->assertNull($clients->getById(12));
    }

    public function testGetByIdWhenClientDoesNotExists(): void
    {
        $clients = new Clients();

        $this->assertNull($clients->getById(12));
    }

    public function testGetById(): void
    {
        $clients = new Clients();

        $clients->add(12);

        $this->assertInstanceOf(Client::class, $clients->getById(12));
    }

    public function testGetClientIdsByUserId(): void
    {
        $clients = new Clients();

        $clients->add(12, new User('id', 'username', 'hash'));

        $this->assertSame([12], $clients->getClientIdsByUserId('id'));
    }

    public function testGetClientIdsByUserIdSkipsInvalidUserIds(): void
    {
        $clients = new Clients();

        $clients->add(11, new User('different-id', 'username', 'hash'));
        $clients->add(12, new User('id', 'username', 'hash'));

        $this->assertSame([12], $clients->getClientIdsByUserId('id'));
    }
}
