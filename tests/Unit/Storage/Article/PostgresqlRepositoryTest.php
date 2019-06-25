<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Storage\Article;

use Amp\Postgres\Link;
use Amp\Postgres\ResultSet;
use Amp\Sql\Statement;
use Amp\Success;
use PeeHaa\FeedMe\Entity\Article;
use PeeHaa\FeedMe\Entity\Articles;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Entity\UserArticles;
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

    public function testGetArticlesByUser(): void
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
                'id'         => 'id',
                'source_id'  => 'source_id',
                'feed_id'    => 'feed_id',
                'url'        => 'url',
                'source'     => 'source',
                'title'      => 'title',
                'excerpt'    => 'excerpt',
                'created_at' => '2019-01-01 12:24:13',
                'read'       => true,
            ])
        ;

        $statement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(new Success($resultSet))
            ->with(['id', 'id'])
        ;

        $link
            ->expects($this->once())
            ->method('prepare')
            ->willReturn(new Success($statement))
        ;

        $repository = new PostgresqlRepository($link);

        $articles = wait($repository->getArticlesByUser(new User('id', 'username', 'hash')));

        $this->assertInstanceOf(UserArticles::class, $articles);
    }

    public function testGetByIdReturnsNullWhenArticleDoesNotExist(): void
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
            ->with(['id'])
        ;

        $link
            ->expects($this->once())
            ->method('prepare')
            ->willReturn(new Success($statement))
        ;

        $repository = new PostgresqlRepository($link);

        $article = wait($repository->getById('id'));

        $this->assertNull($article);
    }

    public function testGetByIdReturnsArticleWhenFound(): void
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
                'source_id'  => 'source_id',
                'feed_id'    => 'feed_id',
                'url'        => 'url',
                'source'     => 'source',
                'title'      => 'title',
                'excerpt'    => 'excerpt',
                'created_at' => '2019-01-01 12:24:13',
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

        $article = wait($repository->getById('id'));

        $this->assertInstanceOf(Article::class, $article);
    }

    public function testMarkAsReadReturnsEarlyWhenAlreadyRead(): void
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
            ->willReturn(['count' => 1])
        ;

        $statement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(new Success($resultSet))
            ->with(['articleId', 'userId'])
        ;

        $link
            ->expects($this->once())
            ->method('prepare')
            ->willReturn(new Success($statement))
        ;

        $repository = new PostgresqlRepository($link);

        wait($repository->markAsRead(
            new Article('articleId', 'sourceId', 'feedId', 'url', 'source', 'title', 'excerpt', new \DateTimeImmutable()),
            new User('userId', 'username', 'hash'),
        ));
    }

    public function testMarkAsReadMarksArticleAsRead(): void
    {
        $link              = $this->createMock(Link::class);
        $isReadStatement   = $this->createMock(Statement::class);
        $markReadStatement = $this->createMock(Statement::class);
        $resultSet         = $this->createMock(ResultSet::class);

        $resultSet
            ->expects($this->once())
            ->method('advance')
            ->willReturn(new Success(true))
        ;

        $resultSet
            ->expects($this->once())
            ->method('getCurrent')
            ->willReturn(['count' => 0])
        ;

        $isReadStatement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(new Success($resultSet))
            ->with(['articleId', 'userId'])
        ;

        $markReadStatement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(new Success())
        ;

        $link
            ->expects($this->exactly(2))
            ->method('prepare')
            ->willReturnOnConsecutiveCalls(new Success($isReadStatement), new Success($markReadStatement))
        ;

        $repository = new PostgresqlRepository($link);

        wait($repository->markAsRead(
            new Article('articleId', 'sourceId', 'feedId', 'url', 'source', 'title', 'excerpt', new \DateTimeImmutable()),
            new User('userId', 'username', 'hash'),
        ));
    }
}
