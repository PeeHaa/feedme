<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Controller;

use Amp\Http\Server\Driver\Client;
use Amp\Http\Server\Request;
use Amp\Http\Server\Response;
use Amp\Http\Server\Router;
use Amp\Http\Server\Session\InMemoryStorage;
use Amp\Http\Server\Session\Session;
use Amp\Http\Status;
use Amp\Success;
use PeeHaa\FeedMe\Controller\StartSession;
use PeeHaa\FeedMe\Entity\Session as SessionEntity;
use PeeHaa\FeedMe\Storage\Session\Repository as SessionRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use function Amp\Promise\wait;

class StartSessionTest extends TestCase
{
    /** @var SessionRepository|MockObject */
    private $sessionRepository;

    /** @var Request */
    private $request;

    public function setUp(): void
    {
        $this->sessionRepository = $this->createMock(SessionRepository::class);

        $this->request = new Request(
            $this->createMock(Client::class),
            'GET',
            $this->createMock(UriInterface::class),
        );
    }

    public function testHandleRequestWhenSessionIsNotValid(): void
    {
        $this->request->setAttribute(Router::class, ['id' => '12345', 'userId' => '67890']);

        $this->sessionRepository
            ->expects($this->once())
            ->method('get')
            ->willReturn(new Success(null))
        ;

        $controller = new StartSession($this->sessionRepository);

        /** @var Response $response */
        $response = wait($controller->handleRequest($this->request));

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Status::UNAUTHORIZED, $response->getStatus());
    }

    public function testHandleRequestWhenSessionIsValid(): void
    {
        $this->request->setAttribute(Router::class, ['id' => '12345', 'userId' => '67890']);
        $this->request->setAttribute('_session', new Session(new InMemoryStorage(), 'session'));

        $this->sessionRepository
            ->expects($this->once())
            ->method('get')
            ->willReturn(new Success(
                new SessionEntity('id', 1, 'userId', 'token', new \DateTimeImmutable()),
            ))
        ;

        $this->sessionRepository
            ->expects($this->once())
            ->method('delete')
            ->willReturn(new Success())
        ;

        $controller = new StartSession($this->sessionRepository);

        /** @var Response $response */
        $response = wait($controller->handleRequest($this->request));

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Status::OK, $response->getStatus());
    }
}
