<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Configuration;

use Amp\Sql\ConnectionConfig;
use PeeHaa\FeedMe\Configuration\Database;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    /** @var Database */
    private $configuration;

    public function setUp(): void
    {
        $this->configuration = new Database('host', 1337, 'username', 'password', 'name');
    }

    public function testGetHostname(): void
    {
        $this->assertSame('host', $this->configuration->getHostname());
    }

    public function testGetPort(): void
    {
        $this->assertSame(1337, $this->configuration->getPort());
    }

    public function testGetUsername(): void
    {
        $this->assertSame('username', $this->configuration->getUsername());
    }

    public function testGetPassword(): void
    {
        $this->assertSame('password', $this->configuration->getPassword());
    }

    public function testGetName(): void
    {
        $this->assertSame('name', $this->configuration->getName());
    }

    public function testToConnectionConfig(): void
    {
        $this->assertInstanceOf(ConnectionConfig::class, $this->configuration->toConnectionConfig());
    }

    public function testFromArray(): void
    {
        $configuration = Database::fromArray([
            'hostname' => 'localhost',
            'port'     => 1337,
            'username' => 'root',
            'password' => 'password',
            'name'     => 'feed_me',
        ]);

        $this->assertSame('localhost', $configuration->getHostname());
        $this->assertSame(1337, $configuration->getPort());
        $this->assertSame('root', $configuration->getUsername());
        $this->assertSame('password', $configuration->getPassword());
        $this->assertSame('feed_me', $configuration->getName());
    }
}
