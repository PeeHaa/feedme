<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Configuration;

class WebServer
{
    /** @var string */
    private $domain;

    /** @var int */
    private $port;

    /** @var bool */
    private $sslEnabled;

    /** @var array<BindAddress> */
    private $addresses = [];

    public function __construct(string $domain, int $port, bool $sslEnabled, BindAddress ...$addresses)
    {
        $this->domain     = $domain;
        $this->port       = $port;
        $this->sslEnabled = $sslEnabled;
        $this->addresses  = $addresses;
    }

    /**
     * @param array<string,mixed> $configuration
     */
    public static function fromArray(array $configuration): self
    {
        $bindAddresses = [];

        foreach ($configuration['bindAddresses'] as $address) {
            $bindAddresses[] = BindAddress::fromString($address);
        }

        return new self(
            $configuration['domain'],
            $configuration['port'],
            $configuration['sslEnabled'],
            ...$bindAddresses,
        );
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function isSslEnabled(): bool
    {
        return $this->sslEnabled;
    }

    /**
     * @return array<BindAddress>
     */
    public function getAddresses(): array
    {
        return $this->addresses;
    }
}
