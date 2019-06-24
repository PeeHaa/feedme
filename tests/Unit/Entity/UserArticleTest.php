<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Entity;

use PeeHaa\FeedMe\Entity\Article;
use PeeHaa\FeedMe\Entity\UserArticle;
use PHPUnit\Framework\TestCase;

class UserArticleTest extends TestCase
{
    /** @var UserArticle */
    private $userArticle;

    public function setUp(): void
    {
        $this->userArticle = new UserArticle(
            new Article('id', 'sourceId', 'feedId', 'url', 'source', 'title', 'excerpt', new \DateTimeImmutable('@0')),
            true,
        );
    }

    public function testGetArticle(): void
    {
        $this->assertInstanceOf(Article::class, $this->userArticle->getArticle());
    }

    public function testIsRead(): void
    {
        $this->assertTrue($this->userArticle->isRead());
    }

    public function testToArray(): void
    {
        $this->assertSame([
            'id'        => 'id',
            'sourceId'  => 'sourceId',
            'feedId'    => 'feedId',
            'url'       => 'url',
            'source'    => 'source',
            'title'     => 'title',
            'excerpt'   => 'excerpt',
            'createdAt' => '1970-01-01 00:00:00',
            'read'      => true,
        ], $this->userArticle->toArray());
    }
}
