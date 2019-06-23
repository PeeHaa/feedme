<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Http\WebSocket;

class Subscription
{
    /** @var string */
    private $feedId;

    /** @var Client */
    private $client;

    public function __construct(string $feedId, Client $client)
    {
        $this->feedId = $feedId;
        $this->client = $client;
    }

    public function getFeedId(): string
    {
        return $this->feedId;
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
