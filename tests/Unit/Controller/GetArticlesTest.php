<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Controller;

use Amp\Success;
use PeeHaa\FeedMe\Collection\UserArticles;
use PeeHaa\FeedMe\Controller\GetArticles;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Request\GetArticles as Request;
use PeeHaa\FeedMe\Response\UserArticles as Response;
use PeeHaa\FeedMe\Storage\Article\Repository;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class GetArticlesTest extends TestCase
{
    public function testProcessRequest(): void
    {
        $articleRepository = $this->createMock(Repository::class);

        $articleRepository
            ->expects($this->once())
            ->method('getArticlesByUser')
            ->willReturn(new Success(new UserArticles()))
        ;

        $controller = new GetArticles($articleRepository);

        $response = wait($controller->processRequest(
            new Request('id', 'GetArticles', new Client(1), new User('1', 'username', 'hash')),
        ));

        $this->assertInstanceOf(Response::class, $response);
    }
}
