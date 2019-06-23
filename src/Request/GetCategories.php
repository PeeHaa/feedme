<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Request;

use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Http\WebSocket\Client;

final class GetCategories extends AuthenticatedRequest
{
    /**
     * @param array<mixed> $requestData
     */
    public static function fromArray(array $requestData, Client $client, User $user): AuthenticatedRequest
    {
        return new self($requestData['id'], $requestData['type'], $client, $user);
    }
}
