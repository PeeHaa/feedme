<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Service\Crawler\Programming\Php;

use Amp\Artax\Client;
use Amp\Artax\Response;
use Amp\ByteStream\InputStream;
use Amp\ByteStream\Message;
use Amp\Success;
use PeeHaa\FeedMe\Service\Crawler\Programming\Php\DocumentationBugs;
use PeeHaa\FeedMe\Service\Parser\Programming\Php\Bugs as Parser;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class DocumentationBugsTest extends TestCase
{
    public function testRetrieve(): void
    {
        $stream = $this->createMock(InputStream::class);

        $stream
            ->method('read')
            ->willReturnOnConsecutiveCalls(
                new Success(file_get_contents(FIXTURES_DIRECTORY . '/Php/bugs.html')),
                new Success(null),
            )
        ;

        $message = new Message($stream);
        $response = $this->createMock(Response::class);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($message)
        ;

        $httpClient = $this->createMock(Client::class);

        $httpClient
            ->expects($this->once())
            ->method('request')
            ->willReturn(new Success($response))
        ;

        $crawler = new DocumentationBugs($httpClient, new Parser());

        wait($crawler->retrieve());
    }
}
