<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Service\Parser\Programming\Php;

use PeeHaa\FeedMe\Collection\Articles;
use PeeHaa\FeedMe\Exception\Feed\MissingNode;
use PeeHaa\FeedMe\Service\Parser\Programming\Php\Bugs;
use PHPUnit\Framework\TestCase;

class BugsTest extends TestCase
{
    /** @var Articles */
    private $articles;

    public function setUp(): void
    {
        $this->articles = (new Bugs())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/bugs.html'));
    }

    public function testParseThrowsOnMissingIdNode(): void
    {
        $this->expectException(MissingNode::class);
        $this->expectExceptionMessage('Source of PHP Bugs is missing the id node');

        (new Bugs())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/bugs-missing-id.html'));
    }

    public function testParseThrowsOnMissingTitleNode(): void
    {
        $this->expectException(MissingNode::class);
        $this->expectExceptionMessage('Source of PHP Bugs is missing the title node');

        (new Bugs())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/bugs-missing-title.html'));
    }

    public function testParseCorrectlyParsesAllBugs(): void
    {
        $this->assertCount(30, $this->articles);
    }

    public function testParseCorrectlyParsesFirstBugsProperties(): void
    {
        $articles = (new Bugs())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/bugs.html'));

        $firstArticle = $articles->current();

        $this->assertRegExp('~[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}~', $firstArticle->getId());
        $this->assertSame('78150', $firstArticle->getSourceId());
        $this->assertSame('FeedId', $firstArticle->getFeedId());
        $this->assertSame('https://bugs.php.net/bug.php?id=78150', $firstArticle->getUrl());
        $this->assertSame('PHP Bugs', $firstArticle->getSource());
        $this->assertSame('Unable to use XMLWriter in Exception-Handler', $firstArticle->getTitle());
        $this->assertSame('', $firstArticle->getExcerpt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $firstArticle->getCreatedAt());
    }

    public function testParseCorrectlyParsesLastBugsProperties(): void
    {
        $articles = (new Bugs())->parse('FeedId', file_get_contents(FIXTURES_DIRECTORY . '/Php/bugs.html'));

        // phpcs:ignore Generic.ControlStructures.InlineControlStructure.NotAllowed
        foreach ($articles as $lastArticle);

        $this->assertRegExp('~[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}~', $lastArticle->getId());
        $this->assertSame('78098', $lastArticle->getSourceId());
        $this->assertSame('FeedId', $lastArticle->getFeedId());
        $this->assertSame('https://bugs.php.net/bug.php?id=78098', $lastArticle->getUrl());
        $this->assertSame('PHP Bugs', $lastArticle->getSource());
        $this->assertSame('Blocking unwanted user from PHP project', $lastArticle->getTitle());
        $this->assertSame('', $lastArticle->getExcerpt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $lastArticle->getCreatedAt());
    }
}
