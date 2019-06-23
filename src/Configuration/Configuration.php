<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Configuration;

class Configuration
{
    /** @var Database */
    private $database;

    /** @var Redis */
    private $redis;

    /** @var WebServer */
    private $webServer;

    public function __construct(Database $database, Redis $redis, WebServer $webServer)
    {
        $this->database  = $database;
        $this->redis     = $redis;
        $this->webServer = $webServer;
    }

    /**
     * @param array<string,array<string,mixed>> $configuration
     */
    public static function fromArray(array $configuration): self
    {
        return new self(
            Database::fromArray($configuration['database']),
            Redis::fromArray($configuration['redis']),
            WebServer::fromArray($configuration['webServer']),
        );
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }

    public function getRedis(): Redis
    {
        return $this->redis;
    }

    public function getWebServer(): WebServer
    {
        return $this->webServer;
    }
}
