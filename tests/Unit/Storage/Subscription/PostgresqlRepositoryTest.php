<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Storage\Subscription;

use Amp\Postgres\Link;
use Amp\Sql\ResultSet;
use Amp\Sql\Statement;
use Amp\Success;
use PeeHaa\FeedMe\Entity\Subscriptions;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Storage\Subscription\PostgresqlRepository;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class PostgresqlRepositoryTest extends TestCase
{
    public function testGetAllByUser(): void
    {
        $link      = $this->createMock(Link::class);
        $statement = $this->createMock(Statement::class);
        $resultSet = $this->createMock(ResultSet::class);

        $resultSet
            ->expects($this->exactly(2))
            ->method('advance')
            ->willReturnOnConsecutiveCalls(new Success(true), new Success(false))
        ;

        $resultSet
            ->expects($this->atLeastOnce())
            ->method('getCurrent')
            ->willReturn([
                'id'          => 'id',
                'feed_id'     => 'feedId',
                'category_id' => 'categoryId',
            ])
        ;

        $statement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(new Success($resultSet))
        ;

        $link
            ->expects($this->once())
            ->method('prepare')
            ->willReturn(new Success($statement))
        ;

        $repository = new PostgresqlRepository($link);

        $this->assertInstanceOf(Subscriptions::class, wait($repository->getAllByUser(
            new User('id', 'username', 'hash'),
        )));
    }
}
