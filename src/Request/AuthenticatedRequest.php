<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Request;

use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Http\WebSocket\Client;

abstract class AuthenticatedRequest extends Request
{
    /** @var User */
    private $user;

    public function __construct(string $id, string $type, Client $client, User $user)
    {
        $this->user = $user;

        parent::__construct($id, $type, $client);
    }

    /**
     * @param array<mixed> $json
     */
    abstract public static function fromArray(array $json, Client $client, User $user): self;

    public function getUser(): User
    {
        return $this->user;
    }
}
