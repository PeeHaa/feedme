<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Collection;

use PeeHaa\FeedMe\Collection\Subscriptions;
use PeeHaa\FeedMe\Entity\Subscription;
use PHPUnit\Framework\TestCase;

class SubscriptionsTest extends TestCase
{
    public function testMemberShouldBeArticle(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Subscriptions::__construct() must be an instance of PeeHaa\FeedMe\Entity\Subscription');

        new Subscriptions(new \DateTimeImmutable());
    }

    public function testIterator(): void
    {
        $articles = new Subscriptions(
            new Subscription('id1', 'feedId1', 'categoryId1'),
            new Subscription('id2', 'feedId2', 'categoryId2'),
        );

        foreach ($articles as $index => $article) {
            $this->assertSame('id' . ($index + 1), $article->getId());
        }
    }
}
