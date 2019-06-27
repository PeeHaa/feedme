<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Collection;

use PeeHaa\FeedMe\Collection\Feeds;
use PeeHaa\FeedMe\Entity\Feed;
use PHPUnit\Framework\TestCase;

class FeedsTest extends TestCase
{
    public function testMemberShouldBeArticle(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Feeds::__construct() must be an instance of PeeHaa\FeedMe\Entity\Feed');

        new Feeds(new \DateTimeImmutable());
    }

    public function testIterator(): void
    {
        $feeds = new Feeds(
            new Feed('id1', 'crawler1', new \DateInterval('P1D')),
            new Feed('id2', 'crawler2', new \DateInterval('P1D')),
        );

        foreach ($feeds as $index => $feed) {
            $this->assertSame('id' . ($index + 1), $feed->getId());
        }
    }

    public function testCount(): void
    {
        $feeds = new Feeds(
            new Feed('id1', 'crawler1', new \DateInterval('P1D')),
            new Feed('id2', 'crawler2', new \DateInterval('P1D')),
        );

        $this->assertCount(2, $feeds);
    }
}
