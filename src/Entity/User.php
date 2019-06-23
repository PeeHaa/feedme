<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Entity;

class User
{
    /** @var string */
    private $id;

    /** @var string */
    private $username;

    /** @var string */
    private $hash;

    public function __construct(string $id, string $username, string $hash)
    {
        $this->id       = $id;
        $this->username = $username;
        $this->hash     = $hash;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getHash(): string
    {
        return $this->hash;
    }
}
