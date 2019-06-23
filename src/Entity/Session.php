<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Entity;

final class Session
{
    /** @var string */
    private $id;

    /** @var int */
    private $clientId;

    /** @var string */
    private $userId;

    /** @var string */
    private $token;

    /** @var \DateTimeImmutable */
    private $expiration;

    public function __construct(string $id, int $clientId, string $userId, string $token, \DateTimeImmutable $expiration)
    {
        $this->id         = $id;
        $this->clientId   = $clientId;
        $this->userId     = $userId;
        $this->token      = $token;
        $this->expiration = $expiration;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpiration(): \DateTimeImmutable
    {
        return $this->expiration;
    }
}
