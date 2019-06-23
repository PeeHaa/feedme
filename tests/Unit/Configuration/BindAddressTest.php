<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Configuration;

use PeeHaa\FeedMe\Configuration\BindAddress;
use PHPUnit\Framework\TestCase;

class BindAddressTest extends TestCase
{
    public function testGetAddress(): void
    {
        $this->assertSame('127.0.0.1', (new BindAddress('127.0.0.1', 1337))->getAddress());
    }

    public function testGetPort(): void
    {
        $this->assertSame(1337, (new BindAddress('127.0.0.1', 1337))->getPort());
    }

    public function testToString(): void
    {
        $this->assertSame('127.0.0.1:1337', (new BindAddress('127.0.0.1', 1337))->toString());
    }

    public function testFromString(): void
    {
        $this->assertSame('127.0.0.1:1337', BindAddress::fromString('127.0.0.1:1337')->toString());
    }

    public function testFromStringHandlesIpv6LoopbackAddress(): void
    {
        $this->assertSame('[::]:1337', BindAddress::fromString('[::]:1337')->toString());
    }
}
