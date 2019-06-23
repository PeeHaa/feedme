<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Service\Crawler\Programming\Php;

use Amp\Artax\Client;
use Amp\Http\Server\Response;
use Amp\Promise;
use PeeHaa\FeedMe\Entity\Articles;
use PeeHaa\FeedMe\Service\Crawler\Crawler;
use PeeHaa\FeedMe\Service\Parser\Programming\Php\Bugs as Parser;
use function Amp\call;

final class DocumentationBugs implements Crawler
{
    private const SOURCE = 'Programming.Php.DocumentationBugs';

    private const ENDPOINT = 'https://bugs.php.net/search.php?limit=30&order_by=id&direction=DESC&cmd=display&status=Open&bug_type=Documentation+Problem';

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
