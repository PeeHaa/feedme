<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Http\WebSocket;

use PeeHaa\FeedMe\Entity\User;

class Client
{
    /** @var int */
    private $id;

    /** @var User|null */
    private $user;

    public function __construct(int $id, ?User $user = null)
    {
        $this->id   = $id;
        $this->user = $user;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
