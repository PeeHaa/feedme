<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Configuration;

use PeeHaa\FeedMe\Configuration\BindAddress;
use PeeHaa\FeedMe\Configuration\Configuration;
use PeeHaa\FeedMe\Configuration\Database;
use PeeHaa\FeedMe\Configuration\Redis;
use PeeHaa\FeedMe\Configuration\WebServer;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    /** @var Configuration */
    private $configuration;

    public function setUp(): void
    {
        $this->configuration =  new Configuration(
            new Database('localhost', 1, 'username', 'password', 'name'),
            new Redis('localhost', 2),
            new WebServer('example.com', 80, false, new BindAddress('127.0.0.1', 80)),
        );
    }

    public function testGetDatabase(): void
    {
        $this->assertInstanceOf(Database::class, $this->configuration->getDatabase());
    }

    public function testGetRedis(): void
    {
        $this->assertInstanceOf(Redis::class, $this->configuration->getRedis());
    }

    public function testGetWebServer(): void
    {
        $this->assertInstanceOf(WebServer::class, $this->configuration->getWebServer());
    }

    public function testFromArray(): void
    {
        $configuration = Configuration::fromArray([
            'database' => [
                'hostname' => 'localhost',
                'port'     => Database::DEFAULT_PORT,
                'username' => 'root',
                'password' => 'password',
                'name'     => 'feed_me',
            ],
            'redis' => [
                'hostname' => 'localhost',
                'port'     => Redis::DEFAULT_PORT,
            ],
            'webServer' => [
                'domain'     => 'localhost',
                'port'       => 1337,
                'sslEnabled' => false,
                'bindAddresses' => [
                    '0.0.0.0:1337',
                    '[::]:1337',
                ],
            ],
        ]);

        $this->assertInstanceOf(Database::class, $configuration->getDatabase());
        $this->assertInstanceOf(Redis::class, $configuration->getRedis());
        $this->assertInstanceOf(WebServer::class, $configuration->getWebServer());
    }
}
