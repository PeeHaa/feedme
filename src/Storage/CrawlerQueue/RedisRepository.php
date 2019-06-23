<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Storage\CrawlerQueue;

use Amp\Promise;
use Amp\Redis\Client;
use PeeHaa\FeedMe\Entity\Feed;
use function Amp\call;

final class RedisRepository implements Repository
{
    private const KEY = 'FeedMeTasks';

    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Promise<int>
     */
    public function clear(): Promise
    {
        return $this->client->del(self::KEY);
    }

    /**
     * @return Promise<null>
     */
    public function enqueue(Feed $feed): Promise
    {
        return $this->enqueueWithScore($feed, (int) (new \DateTimeImmutable())->format('U'));
    }

    /**
     * @return Promise<null>
     */
    public function enqueueWithDelay(Feed $feed): Promise
    {
        return $this->enqueueWithScore($feed, (int) (new \DateTimeImmutable())->add($feed->getInterval())->format('U'));
    }

    /**
     * @return Promise<null>
     */
    private function enqueueWithScore(Feed $feed, int $score): Promise
    {
        return call(function () use ($feed, $score) {
            $transaction = $this->client->transaction();

            yield $transaction->multi();

            yield $transaction->send(['HSET', self::KEY, $feed->getId(), $feed->toJson()]);

            yield $transaction->send(['ZADD', 'due', $score, $feed->getId()]);

            yield $transaction->exec();
        });
    }

    /**
     * @return Promise<Feed|null>
     */
    public function dequeue(): Promise
    {
        return call(function () {
            $ids = yield $this->client->zRangeByScore('due', '-inf', (new \DateTimeImmutable())->format('U'), false, 0, 1);

            if (!$ids) {
                return null;
            }

            $id = $ids[0];

            $feedData = yield $this->client->hGet(self::KEY, $id);

            yield $this->client->zRem('due', $id);
            yield $this->client->hDel(self::KEY, $id);

            $feed = Feed::fromJson($feedData);

            yield $this->enqueueWithDelay($feed);

            return $feed;
        });
    }
}
