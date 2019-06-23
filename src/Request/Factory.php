<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Request;

use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Exception\RequestNotFound;
use PeeHaa\FeedMe\Http\WebSocket\Client;

final class Factory
{
    /**
     * @param array<mixed> $requestData
     */
    public function buildFromArray(Client $client, array $requestData, ?User $user = null): Request
    {
        $requestClass = 'PeeHaa\FeedMe\Request\\' . $requestData['type'];

        if (!class_exists($requestClass)) {
            throw new RequestNotFound($requestClass);
        }

        return $this->buildRequest($client, $requestClass, $requestData, $user);
    }

    /**
     * @param array<mixed> $requestData
     */
    private function buildRequest(Client $client, string $requestClass, array $requestData, ?User $user = null): Request
    {
        if (!$user) {
            /** @var NonAuthenticatedRequest $requestClass */
            return $requestClass::fromArray($requestData, $client);
        }

        /** @var AuthenticatedRequest $requestClass */
        return $requestClass::fromArray($requestData, $client, $user);
    }
}
