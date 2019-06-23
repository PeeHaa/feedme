<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Storage\Feed;

use Amp\Promise;
use PeeHaa\FeedMe\Entity\Feeds;

interface Repository
{
    /**
     * @return Promise<Feeds>
     */
    public function getAll(): Promise;
}
