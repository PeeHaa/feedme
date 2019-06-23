<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Queue;

use Amp\Loop;
use Amp\Success;
use Auryn\Injector;
use PeeHaa\FeedMe\Entity\Feed;
use PeeHaa\FeedMe\Entity\Feeds;
use PeeHaa\FeedMe\Queue\Manager;
use PeeHaa\FeedMe\Storage\CrawlerQueue\Repository as CrawlerQueueRepository;
use PeeHaa\FeedMe\Storage\Feed\Repository as FeedRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use function Amp\Promise\wait;

class ManagerTest extends TestCase
{
    /** @var MockObject|Injector */
    private $injector;

    /** @var MockObject|LoggerInterface */
    private $logger;

    /** @var MockObject|FeedRepository */
    private $feedRepository;

    /** @var MockObject|CrawlerQueueRepository */
    private $crawlerRepository;

    /** @var Manager */
    private $manager;

    public function setUp(): void
    {
        $this->injector = $this->createMock(Injector::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->feedRepository = $this->createMock(FeedRepository::class);
        $this->crawlerRepository = $this->createMock(CrawlerQueueRepository::class);

        $this->manager = new Manager(
            $this->injector,
            $this->logger,
            $this->feedRepository,
            $this->crawlerRepository,
        );
    }

    public function testStartSeedsQueueWithoutFeeds(): void
    {
        $this->feedRepository
            ->expects($this->once())
            ->method('getAll')
            ->willReturn(new Success(new Feeds()))
        ;

        $this->crawlerRepository
            ->expects($this->once())
            ->method('clear')
            ->willReturn(new Success())
        ;

        $this->manager->stop();

        wait($this->manager->start());
    }

    public function testStartSeedsQueueWithFeeds(): void
    {
        $feeds = new Feeds(
            new Feed('id', 'crawler', new \DateInterval('P1D')),
            new Feed('id', 'crawler', new \DateInterval('P1D')),
        );

        $this->feedRepository
            ->expects($this->once())
            ->method('getAll')
            ->willReturn(new Success($feeds))
        ;

        $this->crawlerRepository
            ->expects($this->once())
            ->method('clear')
            ->willReturn(new Success())
        ;

        $this->crawlerRepository
            ->expects($this->exactly(2))
            ->method('enqueue')
            ->willReturn(new Success())
        ;

        $this->manager->stop();

        wait($this->manager->start());
    }

    public function testStartLimitsWorkers(): void
    {
        $this->feedRepository
            ->expects($this->once())
            ->method('getAll')
            ->willReturn(new Success(new Feeds()))
        ;

        $this->crawlerRepository
            ->expects($this->once())
            ->method('clear')
            ->willReturn(new Success())
        ;

        $this->crawlerRepository
            ->expects($this->never())
            ->method('dequeue')
            ->willReturn(new Success())
        ;

        Loop::run(function () {
            $manager = new Manager(
                $this->injector,
                $this->logger,
                $this->feedRepository,
                $this->crawlerRepository,
                0,
            );

            Loop::delay(1500, static function (): void {
                Loop::stop();
            });

            yield $manager->start();
        });
    }

    public function testStartSkipsTickWhenNoFeedIsAvailable(): void
    {
        $this->feedRepository
            ->expects($this->once())
            ->method('getAll')
            ->willReturn(new Success(new Feeds()))
        ;

        $this->crawlerRepository
            ->expects($this->once())
            ->method('clear')
            ->willReturn(new Success())
        ;

        $this->crawlerRepository
            ->expects($this->once())
            ->method('dequeue')
            ->willReturn(new Success(null))
        ;

        Loop::run(function () {
            Loop::defer(static function (): void {
                Loop::stop();
            });

            yield $this->manager->start();
        });
    }

    public function testStartStartsWorker(): void
    {
        $this->feedRepository
            ->expects($this->once())
            ->method('getAll')
            ->willReturn(new Success(new Feeds()))
        ;

        $this->crawlerRepository
            ->expects($this->once())
            ->method('clear')
            ->willReturn(new Success())
        ;

        $this->crawlerRepository
            ->method('dequeue')
            ->willReturnOnConsecutiveCalls(
                new Success(new Feed('id', 'crawler', new \DateInterval('P1D'))),
                new Success(null),
            )
        ;

        $this->logger
            ->expects($this->at(0))
            ->method('debug')
            ->with('Starting a new task for: id')
        ;

        $this->logger
            ->expects($this->at(1))
            ->method('debug')
            ->with('Ended task for: id - added  new articles')
        ;

        $this->injector
            ->method('execute')
            ->willReturn(new Success())
        ;

        Loop::run(function () {
            Loop::defer(static function (): void {
                Loop::stop();
            });

            yield $this->manager->start();
        });
    }
}
