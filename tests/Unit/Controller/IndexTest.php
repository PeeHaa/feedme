<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Controller;

use Amp\Http\Server\Driver\Client;
use Amp\Http\Server\Request;
use Amp\Http\Server\Response;
use Amp\Loop;
use PeeHaa\FeedMe\Controller\Index;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;

class IndexTest extends TestCase
{
    public function testResponseFromIndexController(): void
    {
        Loop::run(function () {
            $request = new Request($this->createMock(Client::class), 'GET', $this->createMock(UriInterface::class));

            /** @var Response $response */
            $response = yield (new Index())->handleRequest($request);

            $this->assertSame(200, $response->getStatus());
            $this->assertArrayHasKey('content-type', $response->getHeaders());
            $this->assertSame('text/html; charset=utf-8', $response->getHeaders()['content-type'][0]);
        });
    }
}
