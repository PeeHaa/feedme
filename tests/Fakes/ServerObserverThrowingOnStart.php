<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Fakes;

use Amp\Failure;
use Amp\Http\Server\Server;
use Amp\Http\Server\ServerObserver;
use Amp\Promise;
use Amp\Success;

class ServerObserverThrowingOnStart implements ServerObserver
{
    /**
     * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     *
     * @return Promise<\Throwable>
     */
    public function onStart(Server $server): Promise
    {
        // phpcs:enable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
        return new Failure(new \Exception('On start throws'));
    }

    /**
     * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     *
     * @return Promise<\Throwable>
     */
    public function onStop(Server $server): Promise
    {
        // phpcs:enable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
        return new Success();
    }
}
