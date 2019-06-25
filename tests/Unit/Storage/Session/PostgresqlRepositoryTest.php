<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Storage\Session;

use Amp\Postgres\Link;
use Amp\Sql\ResultSet;
use Amp\Sql\Statement;
use Amp\Success;
use PeeHaa\FeedMe\Entity\Session;
use PeeHaa\FeedMe\Storage\Session\PostgresqlRepository;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class PostgresqlRepositoryTest extends TestCase
{
    public function testStore(): void
    {
        $link      = $this->createMock(Link::class);
        $statement = $this->createMock(Statement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(new Success())
            ->with(['id', 1, 'userId', 'token', '1970-01-01 00:00:00'])
        ;

        $link
            ->expects($this->once())
            ->method('prepare')
            ->willReturn(new Success($statement))
        ;

        $repository = new PostgresqlRepository($link);

        wait($repository->store(new Session('id', 1, 'userId', 'token', new \DateTimeImmutable('@0'))));
    }

    public function testGetReturnsNullWhenNotFound(): void
    {
        $link      = $this->createMock(Link::class);
        $statement = $this->createMock(Statement::class);
        $resultSet = $this->createMock(ResultSet::class);

        $resultSet
            ->expects($this->once())
            ->method('advance')
            ->willReturn(new Success(false))
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

        $result = wait($repository->get('id', 'userId'));

        $this->assertNull($result);
    }

    public function testGetReturnsSessionWhenFound(): void
    {
        $link      = $this->createMock(Link::class);
        $statement = $this->createMock(Statement::class);
        $resultSet = $this->createMock(ResultSet::class);

        $resultSet
            ->expects($this->once())
            ->method('advance')
            ->willReturn(new Success(true))
        ;

        $resultSet
            ->expects($this->atLeastOnce())
            ->method('getCurrent')
            ->willReturn([
                'id'         => 'id',
                'client_id'  => 1,
                'user_id'    => 'userId',
                'token'      => 'token',
                'expiration' => '2019-01-01 12:24:13',
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

        $result = wait($repository->get('id', 'userId'));

        $this->assertInstanceOf(Session::class, $result);
    }

    public function testDelete(): void
    {
        $link      = $this->createMock(Link::class);
        $statement = $this->createMock(Statement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(new Success())
            ->with(['id'])
        ;

        $link
            ->expects($this->once())
            ->method('prepare')
            ->willReturn(new Success($statement))
        ;

        $repository = new PostgresqlRepository($link);

        wait($repository->delete(new Session('id', 1, 'userId', 'token', new \DateTimeImmutable())));
    }
}
