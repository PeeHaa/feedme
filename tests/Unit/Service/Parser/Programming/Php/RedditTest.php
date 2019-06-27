<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Service\Parser\Programming\Php;

use PeeHaa\FeedMe\Collection\Articles;
use PeeHaa\FeedMe\Exception\Feed\MissingNode;
use PeeHaa\FeedMe\Service\Parser\Programming\Php\Reddit;
use PHPUnit\Framework\TestCase;

class RedditTest extends TestCase
{
    /** @var Articles */
    private $articles;

    public function setUp(): void
    {
        $this->articles = (new Reddit())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/r.php.rss'));
    }

    public function testParseThrowsOnMissingIdNode(): void
    {
        $this->expectException(MissingNode::class);
        $this->expectExceptionMessage('Source of /r/PHP is missing the id node');

        (new Reddit())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/r.php-missing-id.rss'));
    }

    public function testParseThrowsOnMissingLinkNode(): void
    {
        $this->expectException(MissingNode::class);
        $this->expectExceptionMessage('Source of /r/PHP is missing the link node');

        (new Reddit())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/r.php-missing-link.rss'));
    }

    public function testParseThrowsOnMissingTitleNode(): void
    {
        $this->expectException(MissingNode::class);
        $this->expectExceptionMessage('Source of /r/PHP is missing the title node');

        (new Reddit())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/r.php-missing-title.rss'));
    }

    public function testParseThrowsOnMissingContentNode(): void
    {
        $this->expectException(MissingNode::class);
        $this->expectExceptionMessage('Source of /r/PHP is missing the content node');

        (new Reddit())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/r.php-missing-content.rss'));
    }

    public function testParseCorrectlyParsesAllBugs(): void
    {
        $this->assertCount(25, $this->articles);
    }

    public function testParseCorrectlyParsesFirstBugsProperties(): void
    {
        $articles = (new Reddit())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/r.php.rss'));

        $firstArticle = $articles->current();

        $this->assertRegExp('~[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}~', $firstArticle->getId());
        $this->assertSame('t3_c4vw3j', $firstArticle->getSourceId());
        $this->assertSame('FeedId', $firstArticle->getFeedId());
        $this->assertSame('https://www.reddit.com/r/PHP/comments/c4vw3j/monolog_telegram_handler_for_php_71_based_on_cli/', $firstArticle->getUrl());
        $this->assertSame('/r/PHP', $firstArticle->getSource());
        $this->assertSame('Monolog Telegram Handler for PHP 7.1+ based on CLI CURL utility', $firstArticle->getTitle());
        $this->assertRegExp('~/u/afenric~', $firstArticle->getExcerpt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $firstArticle->getCreatedAt());
    }

    public function testParseCorrectlyParsesLastBugsProperties(): void
    {
        $articles = (new Reddit())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/r.php.rss'));

        // phpcs:ignore Generic.ControlStructures.InlineControlStructure.NotAllowed
        foreach ($articles as $lastArticle);

        $this->assertRegExp('~[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}~', $lastArticle->getId());
        $this->assertSame('t3_c25s1b', $lastArticle->getSourceId());
        $this->assertSame('FeedId', $lastArticle->getFeedId());
        $this->assertSame('https://www.reddit.com/r/PHP/comments/c25s1b/install_laravel_framework_on_ubuntu_for_beginners/', $lastArticle->getUrl());
        $this->assertSame('/r/PHP', $lastArticle->getSource());
        $this->assertSame('Install Laravel framework on Ubuntu for beginners using Docker', $lastArticle->getTitle());
        $this->assertRegExp('~/u/beachcasts~', $lastArticle->getExcerpt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $lastArticle->getCreatedAt());
    }
}
