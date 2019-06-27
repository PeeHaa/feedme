<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Service\Crawler\Programming\Php;

use Amp\Artax\Client;
use Amp\Artax\Response;
use Amp\Promise;
use PeeHaa\FeedMe\Collection\Articles;
use PeeHaa\FeedMe\Service\Crawler\Crawler;
use PeeHaa\FeedMe\Service\Parser\Programming\Php\Reddit as Parser;
use function Amp\call;

final class Reddit implements Crawler
{
    private const SOURCE = 'Programming.Php.Reddit';

    private const ENDPOINT = 'https://www.reddit.com/r/php/.rss';

    /** @var Client */
    private $httpClient;

    /** @var Parser */
    private $parser;

    public function __construct(Client $httpClient, Parser $parser)
    {
        $this->httpClient = $httpClient;
        $this->parser     = $parser;
    }

    /**
     * @return Promise<Articles>
     */
    public function retrieve(): Promise
    {
        return call(function () {
            /** @var Response $response */
            $response = yield $this->httpClient->request(self::ENDPOINT);

            return $this->parser->parse(self::SOURCE, yield $response->getBody());
        });
    }
}
