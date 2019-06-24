<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Event;

use Amp\Promise;
use PeeHaa\FeedMe\Entity\Article;
use function Amp\call;
use function PeeHaa\FeedMe\generateUuid;

final class NewArticleManager
{
    /** @var array<callable> */
    private $listeners = [];

    public function listen(callable $listener): string
    {
        $listenerId = generateUuid();

        $this->listeners[$listenerId] = $listener;

        return $listenerId;
    }

    /**
     * @return Promise<null>
     */
    public function publish(Article $article): Promise
    {
        return call(function () use ($article) {
            foreach ($this->listeners as $listener) {
                yield $listener($article);
            }
        });
    }
}
