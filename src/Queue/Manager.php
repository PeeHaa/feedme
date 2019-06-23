<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Queue;

use Amp\Delayed;
use Amp\Promise;
use Auryn\Injector;
use PeeHaa\FeedMe\Entity\Feed;
use PeeHaa\FeedMe\Storage\CrawlerQueue\Repository as CrawlerQueueRepository;
use PeeHaa\FeedMe\Storage\Feed\Repository as FeedRepository;
use Psr\Log\LoggerInterface;
use function Amp\asyncCall;
use function Amp\call;

final class Manager
{
    /** @var Injector */
    private $auryn;

    /** @var LoggerInterface */
    private $logger;

    /** @var FeedRepository */
    private $feedRepository;

    /** @var CrawlerQueueRepository */
    private $crawlerQueueRepository;

    /** @var int */
    private $numberOfWorkers;

    /** @var bool */
    private $running = true;

    /** @var int */
    private $activeWorkers = 0;

    public function __construct(
        Injector $auryn,
        LoggerInterface $logger,
        FeedRepository $feedRepository,
        CrawlerQueueRepository $crawlerQueueRepository,
        int $numberOfWorkers = 10
    ) {
        $this->auryn                  = $auryn;
        $this->logger                 = $logger;
        $this->feedRepository         = $feedRepository;
        $this->crawlerQueueRepository = $crawlerQueueRepository;
        $this->numberOfWorkers        = $numberOfWorkers;
    }

    /**
     * @return Promise<null>
     */
    public function start(): Promise
    {
        return call(function () {
            yield $this->seedQueue();

            while (true) {
                if (!$this->running) {
                    return;
                }

                if ($this->activeWorkers >= $this->numberOfWorkers) {
                    yield new Delayed(1000);

                    continue;
                }

                /** @var Feed|null $feed */
                $feed = yield $this->crawlerQueueRepository->dequeue();

                if ($feed === null) {
                    yield new Delayed(1000);

                    continue;
                }

                asyncCall(function () use ($feed): \Generator {
                    $this->activeWorkers++;

                    $this->logger->debug('Starting a new task for: ' . $feed->getId());

                    $numberOfNewArticles = yield $this->auryn->execute(
                        [Worker::class, 'run'],
                        [$feed],
                    );

                    $this->logger->debug('Ended task for: ' . $feed->getId() . ' - added ' . $numberOfNewArticles . ' new articles');

                    $this->activeWorkers--;
                });
            }
        });
    }

    public function stop(): void
    {
        $this->running = false;
    }

    /**
     * @return Promise<null>
     */
    private function seedQueue(): Promise
    {
        return call(function () {
            yield $this->crawlerQueueRepository->clear();

            $feeds = yield $this->feedRepository->getAll();

            foreach ($feeds as $feed) {
                yield $this->crawlerQueueRepository->enqueue($feed);
            }
        });
    }
}
