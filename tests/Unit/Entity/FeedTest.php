<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Entity;

use PeeHaa\FeedMe\Entity\Feed;
use PHPUnit\Framework\TestCase;

class FeedTest extends TestCase
{
    private const JSON_FIXTURE = '{"id":"TheId","crawler":"TheSpider","interval":"O:12:\"DateInterval\":16:{s:1:\"y\";i:0;s:1:\"m\";i:0;s:1:\"d\";i:1;s:1:\"h\";i:0;s:1:\"i\";i:0;s:1:\"s\";i:0;s:1:\"f\";d:0;s:7:\"weekday\";i:0;s:16:\"weekday_behavior\";i:0;s:17:\"first_last_day_of\";i:0;s:6:\"invert\";i:0;s:4:\"days\";b:0;s:12:\"special_type\";i:0;s:14:\"special_amount\";i:0;s:21:\"have_weekday_relative\";i:0;s:21:\"have_special_relative\";i:0;}"}';

    /** @var Feed */
    private $feed;

    public function setUp(): void
    {
        $this->feed = new Feed('TheId', 'TheSpider', new \DateInterval('P1D'));
    }

    public function testGetId(): void
    {
        $this->assertSame('TheId', $this->feed->getId());
    }

    public function testGetCrawler(): void
    {
        $this->assertSame('TheSpider', $this->feed->getCrawler());
    }

    public function testGetInterval(): void
    {
        $this->assertSame(1, $this->feed->getInterval()->d);
    }

    public function testToJson(): void
    {
        $this->assertSame(self::JSON_FIXTURE, $this->feed->toJson());
    }

    public function testFromJson(): void
    {
        $feed = Feed::fromJson(self::JSON_FIXTURE);

        $this->assertSame('TheId', $feed->getId());
        $this->assertSame('TheSpider', $feed->getCrawler());
        $this->assertSame(1, $feed->getInterval()->d);
    }
}
