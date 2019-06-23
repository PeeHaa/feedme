<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Configuration;

use PeeHaa\FeedMe\Configuration\Redis;
use PHPUnit\Framework\TestCase;

class RedisTest extends TestCase
{
    /** @var Redis */
    private $configuration;

    public function setUp(): void
    {
        $this->configuration = new Redis('localhost', 1337);
    }

    public function testGetHostname(): void
    {
        $this->assertSame('localhost', $this->configuration->getHostname());
    }

    public function testGetPort(): void
    {
        $this->assertSame(1337, $this->configuration->getPort());
    }

    public function testGetUrl(): void
    {
        $this->assertSame('tcp://localhost:1337', $this->configuration->getUrl());
    }

    public function fromArray(): void
    {
        $configuration = Redis::fromArray([
            'hostname' => 'localhost',
            'port'     => 1337,
        ]);

        $this->assertSame('localhost', $configuration->getHostname());
        $this->assertSame(1337, $configuration->getPort());
    }
}
