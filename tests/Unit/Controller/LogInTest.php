<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Controller;

use Amp\Success;
use PeeHaa\FeedMe\Controller\LogIn;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Request\LogIn as Request;
use PeeHaa\FeedMe\Response\LogInInvalid;
use PeeHaa\FeedMe\Response\LogInValid;
use PeeHaa\FeedMe\Storage\Session\Repository as SessionRepository;
use PeeHaa\FeedMe\Storage\User\Repository as UserRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class LogInTest extends TestCase
{
    /** @var MockObject|UserRepository */
    private $userRepository;

    /** @var MockObject|SessionRepository */
    private $sessionRepository;

    /** @var LogIn */
    private $controller;

    /** @var Request */
    private $request;

    public function setUp(): void
    {
        $this->userRepository    = $this->createMock(UserRepository::class);
        $this->sessionRepository = $this->createMock(SessionRepository::class);

        $this->controller     = new LogIn($this->userRepository, $this->sessionRepository);
        $this->request        = new Request(new Client(1), 'TheId', 'LogIn', 'test@example.com', 'MyAwesomePassword');
    }

    public function testProcessRequestReturnsErrorWhenUserDoesNotExist(): void
    {
        $this->userRepository
            ->expects($this->once())
            ->method('getByEmailAddress')
            ->willReturn(new Success(null))
        ;

        $response = wait($this->controller->processRequest($this->request));

        $this->assertInstanceOf(LogInInvalid::class, $response);
    }

    public function testProcessRequestReturnsErrorWhenPasswordDoesNotMatch(): void
    {
        $this->userRepository
            ->expects($this->once())
            ->method('getByEmailAddress')
            ->willReturn(new Success(new User('TheId', 'TheUsername', 'PasswordHash')))
        ;

        $response = wait($this->controller->processRequest($this->request));

        $this->assertInstanceOf(LogInInvalid::class, $response);
    }

    public function testProcessRequestReturnsValidResponseWhenCredentialsAreValid(): void
    {
        $this->userRepository
            ->expects($this->once())
            ->method('getByEmailAddress')
            ->willReturn(new Success(
                new User('TheId', 'TheUsername', '$2y$14$hvpRI1UlxfxuCZU5YW4yy.oad4hQ11ulqy/jODIl1/JN.cmlzdRhS'),
            ))
        ;

        $this->sessionRepository
            ->expects($this->once())
            ->method('store')
            ->willReturn(new Success())
        ;

        $response = wait($this->controller->processRequest($this->request));

        $this->assertInstanceOf(LogInValid::class, $response);
    }
}
