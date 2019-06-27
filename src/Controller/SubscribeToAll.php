<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Controller;

use Amp\Promise;
use PeeHaa\FeedMe\Collection\Subscriptions;
use PeeHaa\FeedMe\Http\WebSocket\Subscription;
use PeeHaa\FeedMe\Http\WebSocket\Subscriptions as PubSubSubscriptions;
use PeeHaa\FeedMe\Request\GetArticles as ArticlesRequest;
use PeeHaa\FeedMe\Request\Request;
use PeeHaa\FeedMe\Response\Response;
use PeeHaa\FeedMe\Response\SubscribedToAll;
use PeeHaa\FeedMe\Storage\Subscription\Repository as SubscriptionRepository;
use function Amp\call;

final class SubscribeToAll implements RequestHandler
{
    /** @var SubscriptionRepository */
    private $subscriptionRepository;

    /** @var PubSubSubscriptions */
    private $pubSubSubscriptions;

    public function __construct(
        SubscriptionRepository $subscriptionRepository,
        PubSubSubscriptions $pubSubSubscriptions
    ) {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->pubSubSubscriptions    = $pubSubSubscriptions;
    }

    /**
     * @return Promise<Response>
     */
    public function processRequest(Request $request): Promise
    {
        /** @var ArticlesRequest $request */
        return call(function () use ($request) {
            /** @var Subscriptions $subscriptions */
            $subscriptions = yield $this->subscriptionRepository->getAllByUser($request->getUser());

            foreach ($subscriptions as $subscription) {
                $this->pubSubSubscriptions->add(new Subscription($subscription->getFeedId(), $request->getClient()));
            }

            return new SubscribedToAll($request->getId());
        });
    }
}
