<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Entity;

use PeeHaa\FeedMe\Entity\Article;
use PeeHaa\FeedMe\Entity\UserArticle;
use PeeHaa\FeedMe\Entity\UserArticles;
use PHPUnit\Framework\TestCase;

class UserArticlesTest extends TestCase
{
    public function testMemberShouldBeArticle(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('UserArticles::__construct() must be an instance of PeeHaa\FeedMe\Entity\UserArticle');

        new UserArticles(new \DateTimeImmutable());
    }

    public function testIterator(): void
    {
        $article = new Article(
            'id',
            'sourceId',
            'feedId',
            'url',
            'source',
            'title',
            'excerpt',
            new \DateTimeImmutable(),
        );

        $articles = new UserArticles(
            new UserArticle($article, false),
            new UserArticle($article, true),
        );

        foreach ($articles as $index => $article) {
            $this->assertSame((bool) $index, $article->isRead());
        }
    }

    public function testAdd(): void
    {
        $article = new Article(
            'id',
            'sourceId',
            'feedId',
            'url',
            'source',
            'title',
            'excerpt',
            new \DateTimeImmutable(),
        );

        $articles = new UserArticles(
            new UserArticle($article, false),
            new UserArticle($article, true),
        );

        $articles->add(new UserArticle($article, false));

        $this->assertCount(3, $articles);
    }

    public function testCount(): void
    {
        $articles = new UserArticles();

        $this->assertCount(0, $articles);

        $article = new Article(
            'id',
            'sourceId',
            'feedId',
            'url',
            'source',
            'title',
            'excerpt',
            new \DateTimeImmutable(),
        );

        $articles->add(new UserArticle($article, false));

        $this->assertCount(1, $articles);
    }

    public function testToArray(): void
    {
        $article = new Article(
            'id',
            'sourceId',
            'feedId',
            'url',
            'source',
            'title',
            'excerpt',
            new \DateTimeImmutable('@0'),
        );

        $articles = new UserArticles(
            new UserArticle($article, false),
            new UserArticle($article, true),
        );

        $this->assertSame([
            [
                'id' => 'id',
                'sourceId'  => 'sourceId',
                'feedId'    => 'feedId',
                'url'       => 'url',
                'source'    => 'source',
                'title'     => 'title',
                'excerpt'   => 'excerpt',
                'createdAt' => '1970-01-01 00:00:00',
                'read'      => false,
            ],
            [
                'id' => 'id',
                'sourceId'  => 'sourceId',
                'feedId'    => 'feedId',
                'url'       => 'url',
                'source'    => 'source',
                'title'     => 'title',
                'excerpt'   => 'excerpt',
                'createdAt' => '1970-01-01 00:00:00',
                'read'      => true,
            ],
        ], $articles->toArray());
    }
}
