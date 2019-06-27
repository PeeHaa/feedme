<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Collection;

use PeeHaa\FeedMe\Collection\Articles;
use PeeHaa\FeedMe\Entity\Article;
use PHPUnit\Framework\TestCase;

class ArticlesTest extends TestCase
{
    public function testMemberShouldBeArticle(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Articles::__construct() must be an instance of PeeHaa\FeedMe\Entity\Article');

        new Articles(new \DateTimeImmutable());
    }

    public function testIterator(): void
    {
        $articles = new Articles(
            new Article('id1', 'sourceId1', 'feedId1', 'url1', 'source1', 'title11', 'excerpt1', new \DateTimeImmutable()),
            new Article('id2', 'sourceId2', 'feedId2', 'url2', 'source2', 'title12', 'excerpt2', new \DateTimeImmutable()),
        );

        foreach ($articles as $index => $article) {
            $this->assertSame('id' . ($index + 1), $article->getId());
        }
    }

    public function testCount(): void
    {
        $articles = new Articles(
            new Article('id1', 'sourceId1', 'feedId1', 'url1', 'source1', 'title11', 'excerpt1', new \DateTimeImmutable()),
            new Article('id2', 'sourceId2', 'feedId2', 'url2', 'source2', 'title12', 'excerpt2', new \DateTimeImmutable()),
        );

        $this->assertCount(2, $articles);
    }

    public function testAdd(): void
    {
        $articles = new Articles(
            new Article('id1', 'sourceId1', 'feedId1', 'url1', 'source1', 'title11', 'excerpt1', new \DateTimeImmutable()),
            new Article('id2', 'sourceId2', 'feedId2', 'url2', 'source2', 'title12', 'excerpt2', new \DateTimeImmutable()),
        );

        $articles->add(
            new Article('id3', 'sourceId3', 'feedId3', 'url3', 'source3', 'title13', 'excerpt3', new \DateTimeImmutable()),
        );

        $this->assertCount(3, $articles);
    }

    public function testToArray(): void
    {
        $articles = new Articles(
            new Article('id1', 'sourceId1', 'feedId1', 'url1', 'source1', 'title1', 'excerpt1', new \DateTimeImmutable()),
            new Article('id2', 'sourceId2', 'feedId2', 'url2', 'source2', 'title2', 'excerpt2', new \DateTimeImmutable()),
        );

        $array = $articles->toArray();

        $this->assertCount(2, $articles->toArray());

        $this->assertSame('id1', $array[0]['id']);
        $this->assertSame('sourceId1', $array[0]['sourceId']);
        $this->assertSame('feedId1', $array[0]['feedId']);
        $this->assertSame('url1', $array[0]['url']);
        $this->assertSame('source1', $array[0]['source']);
        $this->assertSame('title1', $array[0]['title']);
        $this->assertSame('excerpt1', $array[0]['excerpt']);
        $this->assertRegExp('~\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}~', $array[0]['createdAt']);

        $this->assertSame('id2', $array[1]['id']);
        $this->assertSame('sourceId2', $array[1]['sourceId']);
        $this->assertSame('feedId2', $array[1]['feedId']);
        $this->assertSame('url2', $array[1]['url']);
        $this->assertSame('source2', $array[1]['source']);
        $this->assertSame('title2', $array[1]['title']);
        $this->assertSame('excerpt2', $array[1]['excerpt']);
        $this->assertRegExp('~\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}~', $array[1]['createdAt']);
    }
}
