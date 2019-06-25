<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Storage\UserCategory;

use Amp\Postgres\Link;
use Amp\Postgres\ResultSet;
use Amp\Sql\Statement;
use Amp\Success;
use PeeHaa\FeedMe\Entity\Categories;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Storage\UserCategory\PostgresqlRepository;
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
            ->expects($this->exactly(2))
            ->method('getCurrent')
            ->willReturn([
                'category_id'   => 'TheId',
                'category_name' => 'TheName',
            ])
        ;

        $statement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(new Success($resultSet))
            ->with(['id'])
        ;

        $link
            ->expects($this->once())
            ->method('prepare')
            ->willReturn(new Success($statement))
        ;

        $repository = new PostgresqlRepository($link);

        $result = wait($repository->getAllByUser(new User('id', 'username', 'hash')));

        $this->assertInstanceOf(Categories::class, $result);
    }
}
