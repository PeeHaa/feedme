<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Service\Crawler\Programming\Php;

use Amp\Artax\Client;
use Amp\Artax\Response;
use Amp\Promise;
use PeeHaa\FeedMe\Collection\Articles;
use PeeHaa\FeedMe\Service\Crawler\Crawler;
use PeeHaa\FeedMe\Service\Parser\Programming\Php\Releases as Parser;
use function Amp\call;

final class Releases implements Crawler
{
    private const SOURCE = 'Programming.Php.Releases';

    private const ENDPOINT = 'https://www.php.net/releases/feed.php';

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
