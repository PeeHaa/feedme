<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Request;

use PeeHaa\FeedMe\Http\WebSocket\Client;

abstract class Request
{
    /** @var string */
    private $id;

    /** @var string */
    private $type;

    /** @var Client */
    private $client;

    public function __construct(string $id, string $type, Client $client)
    {
        $this->id     = $id;
        $this->type   = $type;
        $this->client = $client;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
