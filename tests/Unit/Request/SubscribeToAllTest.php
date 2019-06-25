<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Request;

use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Request\SubscribeToAll;
use PHPUnit\Framework\TestCase;

class SubscribeToAllTest extends TestCase
{
    public function testFromArray(): void
    {
        $request = SubscribeToAll::fromArray([
            'id'   => 'TheId',
            'type' => 'SubscribeToAll',
        ], new Client(1), new User('id', 'username', 'hash'));

        $this->assertInstanceOf(SubscribeToAll::class, $request);
    }
}
