<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Storage\Feed;

use Amp\Postgres\Link;
use Amp\Postgres\ResultSet;
use Amp\Success;
use PeeHaa\FeedMe\Entity\Feeds;
use PeeHaa\FeedMe\Storage\Feed\PostgresqlRepository;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class PostgresqlRepositoryTest extends TestCase
{
    public function testGetAll(): void
    {
        $link      = $this->createMock(Link::class);
        $resultSet = $this->createMock(ResultSet::class);

        $resultSet
            ->method('advance')
            ->willReturnOnConsecutiveCalls(
                new Success(true),
                new Success(false),
            )
        ;

        $resultSet
            ->expects($this->exactly(3))
            ->method('getCurrent')
            ->willReturn([
                'id'       => '1',
                'crawler'  => 'FooBar',
                'interval' => 'P1D',
            ])
        ;

        $link
            ->expects($this->once())
            ->method('query')
            ->willReturn(new Success($resultSet))
        ;

        $repository = new PostgresqlRepository($link);

        /** @var Feeds $feeds */
        $feeds = wait($repository->getAll());

        $feed = $feeds->current();

        $this->assertCount(1, $feeds);
        $this->assertSame('1', $feed->getId());
        $this->assertSame('FooBar', $feed->getCrawler());
        $this->assertInstanceOf(\DateInterval::class, $feed->getInterval());
    }
}
