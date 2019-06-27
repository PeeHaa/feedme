<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Service\Parser\Programming\Php;

use PeeHaa\FeedMe\Collection\Articles;
use PeeHaa\FeedMe\Entity\Article;
use PeeHaa\FeedMe\Exception\Feed\MissingNode;
use PeeHaa\FeedMe\Service\Parser\Parser;
use function PeeHaa\FeedMe\generateUuid;

final class Bugs implements Parser
{
    private const SOURCE = 'PHP Bugs';

    private const BASE_URL = 'https://bugs.php.net/';

    public function parse(string $feedId, string $source): Articles
    {
        $internalErrors = libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML($source);

        libxml_clear_errors();

        libxml_use_internal_errors($internalErrors);

        $xpath = new \DOMXPath($dom);

        $articles = [];

        /** @var \DOMElement $node */
        foreach ($xpath->evaluate('//tr[@class]') as $node) {
            $articles[] = $this->parseArticle($feedId, $node);
        }

        return new Articles(...$articles);
    }

    private function parseArticle(string $feedId, \DOMElement $node): Article
    {
        $tableCells = $node->getElementsByTagName('td');

        return new Article(
            generateUuid(),
            $this->getId($tableCells),
            $feedId,
            $this->getUrl($tableCells),
            self::SOURCE,
            $this->getTitle($tableCells),
            '',
            (new \DateTimeImmutable()),
        );
    }

    private function getId(\DOMNodeList $nodes): string
    {
        /** @var \DOMElement $idTableCell */
        $idTableCell = $nodes->item(0);

        if ($idTableCell->getElementsByTagName('a')->item(0) === null) {
            throw new MissingNode(self::SOURCE, 'id');
        }

        return $idTableCell->getElementsByTagName('a')->item(0)->textContent;
    }

    private function getUrl(\DOMNodeList $nodes): string
    {
        /** @var \DOMElement $idTableCell */
        $idTableCell = $nodes->item(0);

        /** @var \DOMElement $hyperlink */
        $hyperlink = $idTableCell->getElementsByTagName('a')->item(0);

        return self::BASE_URL . $hyperlink->getAttribute('href');
    }

    private function getTitle(\DOMNodeList $nodes): string
    {
        if ($nodes->item(8) === null) {
            throw new MissingNode(self::SOURCE, 'title');
        }

        return $nodes->item(8)->textContent;
    }
}
