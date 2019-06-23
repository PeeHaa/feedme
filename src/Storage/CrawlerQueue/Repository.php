<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Storage\CrawlerQueue;

use Amp\Promise;
use PeeHaa\FeedMe\Entity\Feed;

interface Repository
{
    /**
     * @return Promise<int>
     */
    public function clear(): Promise;

    /**
     * @return Promise<null>
     */
    public function enqueue(Feed $feed): Promise;

    /**
     * @return Promise<null>
     */
    public function enqueueWithDelay(Feed $feed): Promise;

    /**
     * @return Promise<Feed|null>
     */
    public function dequeue(): Promise;
}
