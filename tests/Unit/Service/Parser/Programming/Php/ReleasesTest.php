<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Service\Parser\Programming\Php;

use PeeHaa\FeedMe\Entity\Articles;
use PeeHaa\FeedMe\Exception\Feed\MissingNode;
use PeeHaa\FeedMe\Service\Parser\Programming\Php\Releases;
use PHPUnit\Framework\TestCase;

class ReleasesTest extends TestCase
{
    /** @var Articles */
    private $articles;

    public function setUp(): void
    {
        $this->articles = (new Releases())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/releases.xml'));
    }

    public function testParseThrowsOnMissingIdNode(): void
    {
        $this->expectException(MissingNode::class);
        $this->expectExceptionMessage('Source of PHP Releases is missing the id node');

        (new Releases())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/releases-missing-id.xml'));
    }

    public function testParseThrowsOnMissingLinkNode(): void
    {
        $this->expectException(MissingNode::class);
        $this->expectExceptionMessage('Source of PHP Releases is missing the url node');

        (new Releases())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/releases-missing-url.xml'));
    }

    public function testParseThrowsOnMissingTitleNode(): void
    {
        $this->expectException(MissingNode::class);
        $this->expectExceptionMessage('Source of PHP Releases is missing the title node');

        (new Releases())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/releases-missing-title.xml'));
    }

    public function testParseThrowsOnMissingContentNode(): void
    {
        $this->expectException(MissingNode::class);
        $this->expectExceptionMessage('Source of PHP Releases is missing the excerpt node');

        (new Releases())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/releases-missing-excerpt.xml'));
    }

    public function testParseCorrectlyParsesAllBugs(): void
    {
        $this->assertCount(3, $this->articles);
    }

    public function testParseCorrectlyParsesFirstBugsProperties(): void
    {
        $articles = (new Releases())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/releases.xml'));

        $firstArticle = $articles->current();

        $this->assertRegExp('~[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}~', $firstArticle->getId());
        $this->assertSame('http://php.net/releases/7_3_6.php', $firstArticle->getSourceId());
        $this->assertSame('FeedId', $firstArticle->getFeedId());
        $this->assertSame('http://php.net/releases/7_3_6.php', $firstArticle->getUrl());
        $this->assertSame('PHP Releases', $firstArticle->getSource());
        $this->assertSame('PHP 7.3.6 released!', $firstArticle->getTitle());
        $this->assertSame('There is a new PHP release in town!', $firstArticle->getExcerpt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $firstArticle->getCreatedAt());
    }

    public function testParseCorrectlyParsesLastBugsProperties(): void
    {
        $articles = (new Releases())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/releases.xml'));

        // phpcs:ignore Generic.ControlStructures.InlineControlStructure.NotAllowed
        foreach ($articles as $lastArticle);

        $this->assertRegExp('~[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}~', $lastArticle->getId());
        $this->assertSame('http://php.net/releases/7_1_30.php', $lastArticle->getSourceId());
        $this->assertSame('FeedId', $lastArticle->getFeedId());
        $this->assertSame('http://php.net/releases/7_1_30.php', $lastArticle->getUrl());
        $this->assertSame('PHP Releases', $lastArticle->getSource());
        $this->assertSame('PHP 7.1.30 released!', $lastArticle->getTitle());
        $this->assertSame('There is a new PHP release in town!', $lastArticle->getExcerpt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $lastArticle->getCreatedAt());
    }
}
