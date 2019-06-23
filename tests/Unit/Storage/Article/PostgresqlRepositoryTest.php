<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Storage\Article;

use Amp\Postgres\Link;
use Amp\Postgres\ResultSet;
use Amp\Sql\Statement;
use Amp\Success;
use PeeHaa\FeedMe\Entity\Article;
use PeeHaa\FeedMe\Entity\Articles;
use PeeHaa\FeedMe\Storage\Article\PostgresqlRepository;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class PostgresqlRepositoryTest extends TestCase
{
    public function testStoreNewArticlesWhenArticleAlreadyExists(): void
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
            ->expects($this->once())
            ->method('getCurrent')
            ->willReturn([
                'count' => 1,
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

        $articles = new Articles(
            new Article(
                'id',
                'sourceId',
                'feedId',
                'https://example.org',
                'source',
                'title',
                'excerpt',
                new \DateTimeImmutable(),
            ),
        );

        wait($repository->storeNewArticles($articles));
    }

    public function testStoreNewArticlesWhenArticleDoesNotExist(): void
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
            ->expects($this->once())
            ->method('getCurrent')
            ->willReturn([
                'count' => 0,
            ])
        ;

        $statement
            ->expects($this->exactly(2))
            ->method('execute')
            ->willReturn(new Success($resultSet))
        ;

        $link
            ->expects($this->exactly(2))
            ->method('prepare')
            ->willReturn(new Success($statement))
        ;


        $repository = new PostgresqlRepository($link);

        $articles = new Articles(
            new Article(
                'id',
                'sourceId',
                'feedId',
                'https://example.org',
                'source',
                'title',
                'excerpt',
                new \DateTimeImmutable(),
            ),
        );

        wait($repository->storeNewArticles($articles));
    }
}
