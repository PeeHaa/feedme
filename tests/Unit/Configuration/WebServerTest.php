<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Configuration;

use PeeHaa\FeedMe\Configuration\BindAddress;
use PeeHaa\FeedMe\Configuration\WebServer;
use PHPUnit\Framework\TestCase;

class WebServerTest extends TestCase
{
    /** @var WebServer */
    private $configuration;

    public function setUp(): void
    {
        $this->configuration = new WebServer('localhost', 1337, false, new BindAddress('localhost', 80));
    }

    public function testGetDomain(): void
    {
        $this->assertSame('localhost', $this->configuration->getDomain());
    }

    public function testGetPort(): void
    {
        $this->assertSame(1337, $this->configuration->getPort());
    }

    public function testIsSslEnabled(): void
    {
        $this->assertFalse($this->configuration->isSslEnabled());
    }

    public function testGetAddresses(): void
    {
        $this->assertCount(1, $this->configuration->getAddresses());
    }

    public function testFromArray(): void
    {
        $configuration = WebServer::fromArray([
            'domain'     => 'localhost',
            'port'       => 1337,
            'sslEnabled' => false,
            'bindAddresses' => [
                '0.0.0.0:1337',
                '[::]:1337',
            ],
        ]);

        $this->assertSame('localhost', $configuration->getDomain());
        $this->assertSame(1337, $configuration->getPort());
        $this->assertFalse($configuration->isSslEnabled());
        $this->assertCount(2, $configuration->getAddresses());
    }
}
