<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Storage\CrawlerQueue;

use Amp\Redis\Client;
use Amp\Redis\Transaction;
use Amp\Success;
use PeeHaa\FeedMe\Entity\Feed;
use PeeHaa\FeedMe\Storage\CrawlerQueue\RedisRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class RedisRepositoryTest extends TestCase
{
    /** @var MockObject|Client */
    private $client;

    /** @var RedisRepository */
    private $repository;

    public function setUp(): void
    {
        $this->client = $this->createMock(Client::class);

        $this->repository =new RedisRepository($this->client);
    }

    public function testClear(): void
    {
        $this->client
            ->expects($this->once())
            ->method('del')
            ->with('FeedMeTasks')
            ->willReturn(new Success())
        ;

        wait($this->repository->clear());
    }

    public function testEnqueue(): void
    {
        $transaction = $this->createMock(Transaction::class);

        $transaction
            ->expects($this->once())
            ->method('multi')
            ->willReturn(new Success())
        ;

        $transaction
            ->expects($this->exactly(2))
            ->method('send')
            ->willReturn(new Success())
        ;

        $transaction
            ->expects($this->once())
            ->method('exec')
            ->willReturn(new Success())
        ;

        $this->client
            ->expects($this->once())
            ->method('transaction')
            ->willReturn($transaction)
        ;

        wait($this->repository->enqueue(new Feed('TheId', 'TheSpider', new \DateInterval('P1D'))));
    }

    public function testEnqueueWithDelay(): void
    {
        $transaction = $this->createMock(Transaction::class);

        $transaction
            ->expects($this->once())
            ->method('multi')
            ->willReturn(new Success())
        ;

        $transaction
            ->expects($this->exactly(2))
            ->method('send')
            ->willReturn(new Success())
        ;

        $transaction
            ->expects($this->once())
            ->method('exec')
            ->willReturn(new Success())
        ;

        $this->client
            ->expects($this->once())
            ->method('transaction')
            ->willReturn($transaction)
        ;

        wait($this->repository->enqueueWithDelay(new Feed('TheId', 'TheSpider', new \DateInterval('P1D'))));
    }

    public function testDequeueWithoutFeeds(): void
    {
        $this->client
            ->expects($this->once())
            ->method('zRangeByScore')
            ->willReturn(new Success([]))
        ;

        $this->client
            ->expects($this->never())
            ->method('hget')
        ;

        wait($this->repository->dequeue());
    }

    public function testDequeueWithFeeds(): void
    {
        $this->client
            ->expects($this->once())
            ->method('zRangeByScore')
            ->willReturn(new Success(['id1', 'id2']))
            ->with('due', '-inf', $this->isType('string'), false, 0, 1)
        ;

        $this->client
            ->expects($this->once())
            ->method('hGet')
            ->with('FeedMeTasks', 'id1')
            ->willReturn(
                new Success('{"id":"TheId","crawler":"TheSpider","interval":"O:12:\"DateInterval\":16:{s:1:\"y\";i:0;s:1:\"m\";i:0;s:1:\"d\";i:1;s:1:\"h\";i:0;s:1:\"i\";i:0;s:1:\"s\";i:0;s:1:\"f\";d:0;s:7:\"weekday\";i:0;s:16:\"weekday_behavior\";i:0;s:17:\"first_last_day_of\";i:0;s:6:\"invert\";i:0;s:4:\"days\";b:0;s:12:\"special_type\";i:0;s:14:\"special_amount\";i:0;s:21:\"have_weekday_relative\";i:0;s:21:\"have_special_relative\";i:0;}"}'),
            )
        ;

        $this->client
            ->expects($this->once())
            ->method('zRem')
            ->with('due', 'id1')
            ->willReturn(new Success())
        ;

        $this->client
            ->expects($this->once())
            ->method('hDel')
            ->with('FeedMeTasks', 'id1')
            ->willReturn(new Success())
        ;

        $transaction = $this->createMock(Transaction::class);

        $transaction
            ->expects($this->once())
            ->method('multi')
            ->willReturn(new Success())
        ;

        $transaction
            ->expects($this->exactly(2))
            ->method('send')
            ->willReturn(new Success())
        ;

        $transaction
            ->expects($this->once())
            ->method('exec')
            ->willReturn(new Success())
        ;

        $this->client
            ->expects($this->once())
            ->method('transaction')
            ->willReturn($transaction)
        ;

        wait($this->repository->dequeue());
    }
}
