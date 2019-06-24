<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Fakes;

use Amp\Success;

class CallableClass
{
    public function __invoke()
    {
        return new Success();
    }
}
