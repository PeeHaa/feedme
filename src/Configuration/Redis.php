<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Configuration;

class Redis
{
    public const DEFAULT_PORT = 6379;

    /** @var string */
    private $hostname;

    /** @var int */
    private $port;

    public function __construct(string $hostname, int $port)
    {
        $this->hostname = $hostname;
        $this->port     = $port;
    }

    /**
     * @param array<string,mixed> $configuration
     */
    public static function fromArray(array $configuration): self
    {
        return new self(
            $configuration['hostname'],
            $configuration['port'],
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

    public function getUrl(): string
    {
        return sprintf('tcp://%s:%d', $this->hostname, $this->port);
    }
}
