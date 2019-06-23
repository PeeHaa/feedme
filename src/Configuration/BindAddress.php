<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Configuration;

class BindAddress
{
    /** @var string */
    private $address;

    /** @var int */
    private $port;

    public function __construct(string $address, int $port)
    {
        $this->address = $address;
        $this->port    = $port;
    }

    public static function fromString(string $address): self
    {
        $parts = explode(':', $address);
        $port  = array_pop($parts);

        return new self(implode(':', $parts), (int) $port);
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function toString(): string
    {
        return sprintf('%s:%d', $this->address, $this->port);
    }
}
