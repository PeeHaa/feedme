<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Service\Parser\Programming\Php;

use PeeHaa\FeedMe\Collection\Articles;
use PeeHaa\FeedMe\Entity\Article;
use PeeHaa\FeedMe\Exception\Feed\MissingNode;
use PeeHaa\FeedMe\Service\Parser\Parser;
use function PeeHaa\FeedMe\generateUuid;

final class Releases implements Parser
{
    private const SOURCE = 'PHP Releases';

    public function parse(string $feedId, string $source): Articles
    {
        $internalErrors = libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML($source);

        libxml_clear_errors();

        libxml_use_internal_errors($internalErrors);

        $articles = [];

        /** @var \DOMElement $node */
        foreach ($dom->getElementsByTagName('entry') as $node) {
            $articles[] = $this->parseArticle($feedId, $node);
        }

        return new Articles(...$articles);
    }

    private function parseArticle(string $feedId, \DOMElement $node): Article
    {
        return new Article(
            generateUuid(),
            $this->getId($node),
            $feedId,
            $this->getUrl($node),
            self::SOURCE,
            $this->getTitle($node),
            $this->getExcerpt($node),
            (new \DateTimeImmutable()),
        );
    }

    private function getId(\DOMElement $node): string
    {
        /** @var \DOMElement|null $idElement */
        $idElement = $node->getElementsByTagName('id')->item(0);

        if ($idElement === null) {
            throw new MissingNode(self::SOURCE, 'id');
        }

        return $idElement->textContent;
    }

    private function getUrl(\DOMElement $node): string
    {
        /** @var \DOMElement|null $urlElement */
        $urlElement = $node->getElementsByTagName('content')->item(0);

        if ($urlElement === null) {
            throw new MissingNode(self::SOURCE, 'url');
        }

        return $urlElement->getAttribute('src');
    }

    private function getTitle(\DOMElement $node): string
    {
        /** @var \DOMElement|null $titleElement */
        $titleElement = $node->getElementsByTagName('title')->item(0);

        if ($titleElement === null) {
            throw new MissingNode(self::SOURCE, 'title');
        }

        return $titleElement->textContent;
    }

    private function getExcerpt(\DOMElement $node): string
    {
        /** @var \DOMElement|null $excerptElement */
        $excerptElement = $node->getElementsByTagName('summary')->item(0);

        if ($excerptElement === null) {
            throw new MissingNode(self::SOURCE, 'excerpt');
        }

        return $excerptElement->textContent;
    }
}
