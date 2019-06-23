<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Entity;

use PeeHaa\FeedMe\Entity\Article;
use PeeHaa\FeedMe\Entity\Articles;
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
}
