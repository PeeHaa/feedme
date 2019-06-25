<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Http\WebSocket;

use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Http\WebSocket\Subscription;
use PeeHaa\FeedMe\Http\WebSocket\Subscriptions;
use PHPUnit\Framework\TestCase;

class SubscriptionsTest extends TestCase
{
    public function testAddWhenFeedIsNotRegistered(): void
    {
        $subscriptions = new Subscriptions();

        $subscriptions->add(new Subscription('feedId', new Client(1)));

        $this->assertSame([1], $subscriptions->getClientIdsByFeedId('feedId'));
    }

    public function testAddWhenClientIsAlreadyRegisteredToFeed(): void
    {
        $subscriptions = new Subscriptions();

        $subscriptions->add(new Subscription('feedId', new Client(1)));
        $subscriptions->add(new Subscription('feedId', new Client(1)));

        $this->assertSame([1], $subscriptions->getClientIdsByFeedId('feedId'));
    }

    public function testAddWhenFeedIsAlreadyRegistered(): void
    {
        $subscriptions = new Subscriptions();

        $subscriptions->add(new Subscription('feedId', new Client(1)));
        $subscriptions->add(new Subscription('feedId', new Client(2)));

        $this->assertSame([1, 2], $subscriptions->getClientIdsByFeedId('feedId'));
    }

    public function testRemoveBySubscription(): void
    {
        $subscriptions = new Subscriptions();

        $subscriptions->add(new Subscription('feedId', new Client(1)));

        $subscriptions->removeBySubscription(new Subscription('feedId', new Client(1)));

        $this->assertSame([], $subscriptions->getClientIdsByFeedId('feedId'));
    }

    public function testRemoveByClient(): void
    {
        $subscriptions = new Subscriptions();

        $subscriptions->add(new Subscription('feedId', new Client(1)));

        $subscriptions->removeByClient(new Client(1));

        $this->assertSame([], $subscriptions->getClientIdsByFeedId('feedId'));
    }

    public function testGetClientIdsWhenFeedIsNotRegistered(): void
    {
        $subscriptions = new Subscriptions();

        $this->assertSame([], $subscriptions->getClientIdsByFeedId('feedId'));
    }

    public function testGetClientIds(): void
    {
        $subscriptions = new Subscriptions();

        $subscriptions->add(new Subscription('feedId', new Client(1)));
        $subscriptions->add(new Subscription('feedId', new Client(2)));
        $subscriptions->add(new Subscription('feedId', new Client(3)));
        $subscriptions->add(new Subscription('feedId', new Client(3)));

        $this->assertSame([1, 2, 3], $subscriptions->getClientIdsByFeedId('feedId'));
    }
}
