<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Http\WebSocket;

use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Http\WebSocket\Subscription;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    public function testGetFeedId(): void
    {
        $this->assertSame('feedId', (new Subscription('feedId', new Client(1)))->getFeedId());
    }

    public function testGetClient(): void
    {
        $client = (new Subscription('feedId', new Client(1)))->getClient();

        $this->assertInstanceOf(Client::class, $client);
        $this->assertSame(1, $client->getId());
    }
}
