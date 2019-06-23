<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Storage\User;

use Amp\Postgres\Link;
use Amp\Postgres\ResultSet;
use Amp\Sql\Statement;
use Amp\Success;
use PeeHaa\FeedMe\Entity\NewUser;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Storage\User\PostgresqlRepository;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class PostgresqlRepositoryTest extends TestCase
{
    public function testCreate(): void
    {
        $link      = $this->createMock(Link::class);
        $statement = $this->createMock(Statement::class);

        $statement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(new Success())
        ;

        $link
            ->expects($this->once())
            ->method('prepare')
            ->willReturn(new Success($statement))
        ;

        $repository = new PostgresqlRepository($link);

        wait($repository->create(
            new NewUser('TheId', 'TheUsername', 'ThePassword'),
        ));
    }

    public function testGetByEmailAddressReturnsNullWhenEmailAddressDoesNotExist(): void
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
            ->with(['f1b5142c98193685d86daaf3fc5c48b503d385772c0b5f672b09066199fb7b3ff04474dabcd2a3592702de6f07fd6590f1f84b15497893dccfe6679c04926358'])
        ;

        $link
            ->expects($this->once())
            ->method('prepare')
            ->willReturn(new Success($statement))
        ;

        $repository = new PostgresqlRepository($link);

        $this->assertNull(wait($repository->getByEmailAddress('EmailAddress')));
    }

    public function testGetByEmailAddressReturnsUser(): void
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
            ->expects($this->exactly(3))
            ->method('getCurrent')
            ->willReturn([
                'id'            => 'TheId',
                'email_address' => 'test@example.com',
                'password'      => 'ThePassword',
            ])
        ;

        $statement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(new Success($resultSet))
            ->with(['f1b5142c98193685d86daaf3fc5c48b503d385772c0b5f672b09066199fb7b3ff04474dabcd2a3592702de6f07fd6590f1f84b15497893dccfe6679c04926358'])
        ;

        $link
            ->expects($this->once())
            ->method('prepare')
            ->willReturn(new Success($statement))
        ;

        $repository = new PostgresqlRepository($link);

        /** @var User $user */
        $user = wait($repository->getByEmailAddress('EmailAddress'));

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('TheId', $user->getId());
        $this->assertSame('test@example.com', $user->getUsername());
        $this->assertSame('ThePassword', $user->getHash());
    }
}
