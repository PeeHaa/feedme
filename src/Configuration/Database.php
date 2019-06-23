<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Configuration;

use Amp\Postgres\ConnectionConfig;

class Database
{
    public const DEFAULT_PORT = 5432;

    /** @var string */
    private $hostname;

    /** @var int */
    private $port;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $name;

    public function __construct(string $hostname, int $port, string $username, string $password, string $name)
    {
        $this->hostname = $hostname;
        $this->port     = $port;
        $this->username = $username;
        $this->password = $password;
        $this->name     = $name;
    }

    /**
     * @param array<string,mixed> $configuration
     */
    public static function fromArray(array $configuration): self
    {
        return new self(
            $configuration['hostname'],
            $configuration['port'],
            $configuration['username'],
            $configuration['password'],
            $configuration['name'],
        );
    }

    public function getHostname(): string
    {
        return $this->hostname;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toConnectionConfig(): ConnectionConfig
    {
        return new ConnectionConfig($this->hostname, $this->port, $this->username, $this->password, $this->name);
    }
}
