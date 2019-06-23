<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Http;

use Amp\Delayed;
use Amp\Http\Server\RequestHandler\CallableRequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Server\Server as AmpServer;
use Amp\Http\Status;
use Amp\MultiReasonException;
use PeeHaa\FeedMe\Http\Server;
use PeeHaa\FeedMeTest\Fakes\ServerObserverThrowingOnStart;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use function Amp\Promise\wait;
use function Amp\Socket\listen;

class ServerTest extends TestCase
{
    public function testServerExceptionGetsRethrown(): void
    {
        $socket = listen('tcp://127.0.0.1:0');

        $server = new AmpServer([$socket], new CallableRequestHandler(static function () {
            yield new Delayed(2000);

            return new Response(Status::NO_CONTENT);
        }), $this->createMock(LoggerInterface::class));

        $server->attach(new ServerObserverThrowingOnStart());
        $server->attach(new ServerObserverThrowingOnStart());

        $this->expectException(MultiReasonException::class);

        wait((new Server(new NullLogger(), $server))->start());
    }

    public function testServerExceptionLogsAll(): void
    {
        $loggerMock = $this->createMock(LoggerInterface::class);

        $loggerMock
            ->expects($this->exactly(2))
            ->method('critical')
            ->with('On start throws')
        ;

        $socket = listen('tcp://127.0.0.1:0');

        $server = new AmpServer([$socket], new CallableRequestHandler(static function () {
            yield new Delayed(2000);

            return new Response(Status::NO_CONTENT);
        }), $loggerMock);

        $server->attach(new ServerObserverThrowingOnStart());
        $server->attach(new ServerObserverThrowingOnStart());

        $this->expectException(MultiReasonException::class);

        wait((new Server($loggerMock, $server))->start());
    }
}
