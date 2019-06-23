<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Entity;

use PeeHaa\FeedMe\Entity\Article;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    /** @var Article */
    private $article;

    public function setUp(): void
    {
        $this->article = new Article(
            'TheId',
            'TheSourceId',
            'TheFeedId',
            'https://example.com',
            'TheSource',
            'TheTitle',
            'TheExcerpt',
            new \DateTimeImmutable('@0'),
        );
    }

    public function testGetId(): void
    {
        $this->assertSame('TheId', $this->article->getId());
    }

    public function testGetSourceId(): void
    {
        $this->assertSame('TheSourceId', $this->article->getSourceId());
    }

    public function testGetFeedId(): void
    {
        $this->assertSame('TheFeedId', $this->article->getFeedId());
    }

    public function testGetUrl(): void
    {
        $this->assertSame('https://example.com', $this->article->getUrl());
    }

    public function testGetSource(): void
    {
        $this->assertSame('TheSource', $this->article->getSource());
    }

    public function testGetTitle(): void
    {
        $this->assertSame('TheTitle', $this->article->getTitle());
    }

    public function testGetExcerpt(): void
    {
        $this->assertSame('TheExcerpt', $this->article->getExcerpt());
    }

    public function testGetCreatedAt(): void
    {
        $this->assertSame('1970-01-01 00:00:00', $this->article->getCreatedAt()->format('Y-m-d H:i:s'));
    }

    public function testToArray(): void
    {
        $data = [
            'id'        => 'TheId',
            'sourceId'  => 'TheSourceId',
            'feedId'    => 'TheFeedId',
            'url'       => 'https://example.com',
            'source'    => 'TheSource',
            'title'     => 'TheTitle',
            'excerpt'   => 'TheExcerpt',
            'createdAt' => '1970-01-01 00:00:00',
        ];

        $this->assertSame($data, $this->article->toArray());
    }
}
