<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Http\WebSocket;

use PeeHaa\FeedMe\Entity\User;

class Clients
{
    /** @var array<int,Client> */
    private $clients = [];

    public function add(int $clientId, ?User $user = null): void
    {
        $this->clients[$clientId] = new Client($clientId, $user);
    }

    public function removeById(int $clientId): void
    {
        unset($this->clients[$clientId]);
    }

    public function getById(int $clientId): ?Client
    {
        if (!isset($this->clients[$clientId])) {
            return null;
        }

        return $this->clients[$clientId];
    }

    /**
     * @return array<int>
     */
    public function getClientIdsByUserId(string $userId): array
    {
        $clientIds = [];

        foreach ($this->clients as $client) {
            if (!$client->getUser() || $client->getUser()->getId() !== $userId) {
                continue;
            }

            $clientIds[] = $client->getId();
        }

        return $clientIds;
    }
}
