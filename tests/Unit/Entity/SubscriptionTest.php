<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Entity;

use PeeHaa\FeedMe\Entity\Subscription;
use PHPUnit\Framework\TestCase;

class SubscriptionTest extends TestCase
{
    /** @var Subscription */
    private $subscription;

    public function setUp(): void
    {
        $this->subscription = new Subscription('id', 'feedId', 'categoryId');
    }

    public function testGetId(): void
    {
        $this->assertSame('id', $this->subscription->getId());
    }

    public function testGetFeedId(): void
    {
        $this->assertSame('feedId', $this->subscription->getFeedId());
    }

    public function testGetCategoryId(): void
    {
        $this->assertSame('categoryId', $this->subscription->getCategoryId());
    }
}
