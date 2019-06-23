<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Event;

use PeeHaa\FeedMe\Entity\Article;
use function Amp\asyncCall;
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

    public function publish(Article $article): void
    {
        asyncCall(function () use ($article) {
            foreach ($this->listeners as $listener) {
                yield $listener($article);
            }
        });
    }
}
