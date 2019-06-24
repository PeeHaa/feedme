<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Http;

use Amp\Http\Server\Driver\Client;
use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Server\Server;
use PeeHaa\FeedMe\Configuration\BindAddress;
use PeeHaa\FeedMe\Configuration\WebServer;
use PeeHaa\FeedMe\Event\NewArticleManager;
use PeeHaa\FeedMe\FrontController;
use PeeHaa\FeedMe\Http\WebSocket;
use PeeHaa\FeedMe\Http\WebSocket\Subscriptions;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;
use function Amp\Promise\wait;
use function Amp\Socket\listen;

class WebSocketTest extends TestCase
{
    /** @var FrontController|MockObject */
    private $frontController;

    /** @var LoggerInterface|MockObject */
    private $logger;

    /** @var WebSocket */
    private $webSocket;

    public function setUp(): void
    {
        $this->frontController = $this->createMock(FrontController::class);
        $this->logger          = $this->createMock(LoggerInterface::class);

        $this->webSocket = new WebSocket(
            $this->frontController,
            new Subscriptions(),
            new NewArticleManager(),
            new WebServer('example.com', 80, false, new BindAddress('0.0.0.0', 80)),
            $this->logger,
        );
    }

    public function testOnStartLogs(): void
    {
        $this->logger
            ->expects($this->once())
            ->method('info')
            ->with('WebSocket server started')
        ;

        $socket = listen('tcp://127.0.0.1:0');

        $server = new Server([$socket], $this->createMock(RequestHandler::class), $this->createMock(LoggerInterface::class));

        wait($this->webSocket->onStart($server));
    }

    public function testOnStopLogs(): void
    {
        $this->logger
            ->expects($this->once())
            ->method('info')
            ->with('WebSocket server stopped')
        ;

        $socket = listen('tcp://127.0.0.1:0');

        $server = new Server([$socket], $this->createMock(RequestHandler::class), $this->createMock(LoggerInterface::class));

        wait($this->webSocket->onStop($server));
    }

    public function testOnHandshakeReturns403ResponseOnInvalidOrigin(): void
    {
        $request  = new Request($this->createMock(Client::class), 'GET', $this->createMock(UriInterface::class));

        $request->setHeader('origin', 'http://notexample.com:80');

        $response = $this->webSocket->onHandshake($request, new Response());

        $this->assertSame(403, $response->getStatus());
    }

    public function testOnHandshakeReturns200ResponseOnValidOrigin(): void
    {
        $request  = new Request($this->createMock(Client::class), 'GET', $this->createMock(UriInterface::class));

        $request->setHeader('origin', 'http://example.com:80');

        $response = $this->webSocket->onHandshake($request, new Response());

        $this->assertSame(200, $response->getStatus());
    }

    public function testOnOpenLogs(): void
    {
        $this->logger
            ->expects($this->at(0))
            ->method('info')
            ->with('WebSocket server started')
        ;

        $this->logger
            ->expects($this->at(1))
            ->method('info')
            ->with('New WebSocket client connected')
        ;

        $socket = listen('tcp://127.0.0.1:0');

        $server = new Server([$socket], $this->createMock(RequestHandler::class), $this->createMock(LoggerInterface::class));

        wait($this->webSocket->onStart($server));

        $request  = new Request($this->createMock(Client::class), 'GET', $this->createMock(UriInterface::class));

        $this->webSocket->onOpen(1, $request);
    }

    public function testOnCloseLogs(): void
    {
        $this->logger
            ->expects($this->at(0))
            ->method('info')
            ->with('WebSocket server started')
        ;

        $this->logger
            ->expects($this->at(1))
            ->method('info')
            ->with('New WebSocket client connected')
        ;

        $this->logger
            ->expects($this->at(2))
            ->method('info')
            ->with('WebSocket client disconnected')
        ;

        $socket = listen('tcp://127.0.0.1:0');

        $server = new Server([$socket], $this->createMock(RequestHandler::class), $this->createMock(LoggerInterface::class));

        wait($this->webSocket->onStart($server));

        $request = new Request($this->createMock(Client::class), 'GET', $this->createMock(UriInterface::class));

        $this->webSocket->onOpen(1, $request);

        $this->webSocket->onClose(1, 200, 'no reason');
    }
}
