<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Entity;

class NewUser
{
    /** @var string */
    private $id;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    public function __construct(string $id, string $username, string $password)
    {
        $this->id       = $id;
        $this->username = $username;
        $this->password = $password;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
