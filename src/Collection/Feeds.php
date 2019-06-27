<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Collection;

use PeeHaa\FeedMe\Entity\Feed;

final class Feeds implements \Iterator, \Countable
{
    /** @var array<Feed> */
    private $feeds = [];

    public function __construct(Feed ...$feeds)
    {
        $this->feeds = $feeds;
    }

    public function current(): Feed
    {
        return current($this->feeds);
    }

    public function next(): void
    {
        next($this->feeds);
    }

    /**
     * @return int|string|null
     */
    public function key()
    {
        return key($this->feeds);
    }

    public function valid(): bool
    {
        return $this->key() !== null;
    }

    public function rewind(): void
    {
        reset($this->feeds);
    }

    public function count(): int
    {
        return count($this->feeds);
    }
}
