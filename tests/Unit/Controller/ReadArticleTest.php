<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Controller;

use Amp\Http\Server\Driver\Client;
use Amp\Http\Server\Request;
use Amp\Http\Server\Response;
use Amp\Http\Server\Router;
use Amp\Http\Server\Session\InMemoryStorage;
use Amp\Http\Server\Session\Session;
use Amp\Success;
use PeeHaa\FeedMe\Controller\ReadArticle;
use PeeHaa\FeedMe\Entity\Article;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Http\WebSocket;
use PeeHaa\FeedMe\Storage\Article\Repository as ArticleRepository;
use PeeHaa\FeedMe\Storage\User\Repository as UserRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use function Amp\Promise\wait;

class ReadArticleTest extends TestCase
{
    /** @var ArticleRepository|MockObject */
    private $articleRepository;

    /** @var UserRepository|MockObject */
    private $userRepository;

    /** @var WebSocket|MockObject */
    private $webSocket;

    /** @var Request */
    private $request;

    public function setUp(): void
    {
        $this->articleRepository = $this->createMock(ArticleRepository::class);
        $this->userRepository    = $this->createMock(UserRepository::class);
        $this->webSocket         = $this->createMock(WebSocket::class);

        $this->request = new Request(
            $this->createMock(Client::class),
            'GET',
            $this->createMock(UriInterface::class),
        );
    }

    public function testHandleRequestWhenArticleCouldNotBeFound(): void
    {
        $this->markTestIncomplete('Article not found handling needs to be implemented still');
    }

    public function testHandleRequestWhenUserIsNotLoggedIn(): void
    {
        $this->request->setAttribute(Router::class, ['id' => '123456']);
        $this->request->setAttribute('_session', new Session(new InMemoryStorage(), 'session'));

        $this->articleRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn(new Success(
                new Article('id', 'sourceId', 'feedId', 'url', 'source', 'title', 'excerpt', new \DateTimeImmutable()),
            ))
        ;

        $controller = new ReadArticle($this->articleRepository, $this->userRepository, $this->webSocket);

        /** @var Response $response */
        $response = wait($controller->handleRequest($this->request));

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testHandleRequestWhenUserDoesNotExist(): void
    {
        $session = new Session(new InMemoryStorage(), '_session');

        wait($session->open());

        $session->set('userId', 'userId');

        wait($session->save());

        $this->request->setAttribute(Router::class, ['id' => '123456']);
        $this->request->setAttribute('_session', $session);

        $this->articleRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn(new Success(
                new Article('id', 'sourceId', 'feedId', 'url', 'source', 'title', 'excerpt', new \DateTimeImmutable()),
            ))
        ;

        $this->userRepository
            ->expects($this->once())
            ->method('getById')
            ->with('userId')
            ->willReturn(new Success(null))
        ;

        $controller = new ReadArticle($this->articleRepository, $this->userRepository, $this->webSocket);

        /** @var Response $response */
        $response = wait($controller->handleRequest($this->request));

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testHandleRequestUpdatesReadStatus(): void
    {
        $session = new Session(new InMemoryStorage(), '_session');

        wait($session->open());

        $session->set('userId', 'userId');

        wait($session->save());

        $this->request->setAttribute(Router::class, ['id' => '123456']);
        $this->request->setAttribute('_session', $session);

        $this->articleRepository
            ->expects($this->once())
            ->method('getById')
            ->willReturn(new Success(
                new Article('id', 'sourceId', 'feedId', 'url', 'source', 'title', 'excerpt', new \DateTimeImmutable()),
            ))
        ;

        $this->articleRepository
            ->expects($this->once())
            ->method('markAsRead')
            ->willReturn(new Success())
        ;

        $this->userRepository
            ->expects($this->once())
            ->method('getById')
            ->with('userId')
            ->willReturn(new Success(
                new User('id', 'username', 'hash'),
            ))
        ;

        $this->webSocket
            ->expects($this->once())
            ->method('markArticleAsReadForUser')
            ->willReturn(new Success())
        ;

        $controller = new ReadArticle($this->articleRepository, $this->userRepository, $this->webSocket);

        /** @var Response $response */
        $response = wait($controller->handleRequest($this->request));

        $this->assertInstanceOf(Response::class, $response);
    }
}
