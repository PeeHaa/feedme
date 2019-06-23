<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Request;

use PeeHaa\FeedMe\Http\WebSocket\Client;

abstract class NonAuthenticatedRequest extends Request
{
    /**
     * @param array<mixed> $json
     */
    abstract public static function fromArray(array $json, Client $client): self;
}
