<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Collection;

use PeeHaa\FeedMe\Entity\Subscription;

final class Subscriptions implements \Iterator
{
    /** @var array<Subscription> */
    private $subscriptions = [];

    public function __construct(Subscription ...$subscriptions)
    {
        $this->subscriptions = $subscriptions;
    }

    public function current(): Subscription
    {
        return current($this->subscriptions);
    }

    public function next(): void
    {
        next($this->subscriptions);
    }

    /**
     * @return int|string|null
     */
    public function key()
    {
        return key($this->subscriptions);
    }

    public function valid(): bool
    {
        return $this->key() !== null;
    }

    public function rewind(): void
    {
        reset($this->subscriptions);
    }
}
