<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Http\WebSocket;

class Subscriptions
{
    /** @var array<string,array<Subscription>> */
    private $subscriptions = [];

    public function add(Subscription $subscription): void
    {
        if (!isset($this->subscriptions[$subscription->getFeedId()])) {
            $this->subscriptions[$subscription->getFeedId()] = [];
        }

        if ($this->isSubscribed($subscription)) {
            return;
        }

        $this->subscriptions[$subscription->getFeedId()][$subscription->getClient()->getId()] = $subscription;
    }

    private function isSubscribed(Subscription $subscription): bool
    {
        return isset($this->subscriptions[$subscription->getFeedId()][$subscription->getClient()->getId()]);
    }

    public function removeBySubscription(Subscription $subscription): void
    {
        unset($this->subscriptions[$subscription->getFeedId()][$subscription->getClient()->getId()]);
    }

    public function removeByClient(Client $client): void
    {
        // phpcs:ignore SlevomatCodingStandard.Variables.UnusedVariable.UnusedVariable
        foreach ($this->subscriptions as $feedId => $subscription) {
            $this->removeBySubscription(new Subscription($feedId, $client));
        }
    }

    /**
     * @return array<int>
     */
    public function getClientIdsByFeedId(string $feedId): array
    {
        if (!isset($this->subscriptions[$feedId])) {
            return [];
        }

        $clientIds = [];

        foreach ($this->subscriptions[$feedId] as $subscription) {
            $clientIds[] = $subscription->getClient()->getId();
        }

        return $clientIds;
    }
}
