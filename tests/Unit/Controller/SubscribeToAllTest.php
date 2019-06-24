<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Controller;

use Amp\Success;
use PeeHaa\FeedMe\Controller\SubscribeToAll;
use PeeHaa\FeedMe\Entity\Subscription;
use PeeHaa\FeedMe\Entity\Subscriptions;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Http\WebSocket\Subscriptions as PubSubSubscriptions;
use PeeHaa\FeedMe\Request\GetArticles;
use PeeHaa\FeedMe\Response\SubscribedToAll;
use PeeHaa\FeedMe\Storage\Subscription\Repository;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class SubscribeToAllTest extends TestCase
{
    public function testProcessRequestWithoutSubscriptions(): void
    {
        $subscriptionRepository = $this->createMock(Repository::class);
        $subscriptions          = new PubSubSubscriptions();

        $subscriptionRepository
            ->expects($this->once())
            ->method('getAllByUser')
            ->willReturn(new Success(new Subscriptions()))
        ;

        $controller = new SubscribeToAll($subscriptionRepository, $subscriptions);

        $response = wait($controller->processRequest(
            new GetArticles('id', 'GetArticles', new Client(1), new User('id', 'username', 'hash')),
        ));

        $this->assertInstanceOf(SubscribedToAll::class, $response);
    }

    public function testProcessRequestWithSubscriptions(): void
    {
        $subscriptionRepository = $this->createMock(Repository::class);
        $subscriptions          = new PubSubSubscriptions();

        $subscriptionRepository
            ->expects($this->once())
            ->method('getAllByUser')
            ->willReturn(new Success(new Subscriptions(
                new Subscription('id', 'feedId', 'categoryId'),
            )))
        ;

        $controller = new SubscribeToAll($subscriptionRepository, $subscriptions);

        $response = wait($controller->processRequest(
            new GetArticles('id', 'GetArticles', new Client(1), new User('id', 'username', 'hash')),
        ));

        $this->assertInstanceOf(SubscribedToAll::class, $response);
    }
}
