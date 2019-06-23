<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Http;

use Amp\Http\Server\Server as AmpServer;
use Amp\MultiReasonException;
use Amp\Promise;
use Psr\Log\LoggerInterface;
use function Amp\call;

final class Server
{
    /** @var LoggerInterface */
    private $logger;

    /** @var AmpServer */
    private $server;

    public function __construct(LoggerInterface $logger, AmpServer $server)
    {
        $this->logger = $logger;
        $this->server = $server;
    }

    /**
     * @return Promise<null>
     */
    public function start(): Promise
    {
        return call(function () {
            try {
                yield $this->server->start();
            } catch (MultiReasonException $e) {
                foreach ($e->getReasons() as $exception) {
                    $this->logger->critical($exception->getMessage());
                }

                throw $e;
            }
        });
    }
}
