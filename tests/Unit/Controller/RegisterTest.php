<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Controller;

use Amp\Success;
use PeeHaa\FeedMe\Controller\Register;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Request\Register as Request;
use PeeHaa\FeedMe\Response\Error;
use PeeHaa\FeedMe\Response\RegisterValid;
use PeeHaa\FeedMe\Storage\Session\Repository as SessionRepository;
use PeeHaa\FeedMe\Storage\User\Repository as UserRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class RegisterTest extends TestCase
{
    /** @var MockObject|UserRepository */
    private $userRepository;

    /** @var MockObject|SessionRepository */
    private $sessionRepository;

    /** @var Register */
    private $controller;

    /** @var Request */
    private $request;

    public function setUp(): void
    {
        $this->userRepository    = $this->createMock(UserRepository::class);
        $this->sessionRepository = $this->createMock(SessionRepository::class);

        $this->controller = new Register($this->userRepository, $this->sessionRepository);
        $this->request    = new Request(new Client(1), 'TheId', 'LogIn', 'test@example.com', 'ThePassword', 'ThePassword');
    }

    public function testProcessRequestReturnsErrorWhenUserAlreadyExists(): void
    {
        $this->userRepository
            ->expects($this->once())
            ->method('getByEmailAddress')
            ->willReturn(new Success(new User('TheId', 'TheUsername', 'PasswordHash')))
        ;

        $response = wait($this->controller->processRequest($this->request));

        $this->assertInstanceOf(Error::class, $response);
    }

    public function testProcessRequestReturnsValidResponseWhenNewUserIsValid(): void
    {
        $this->userRepository
            ->method('getByEmailAddress')
            ->willReturnOnConsecutiveCalls(
                new Success(null),
                new Success(new User(
                    'bd145115-a21d-4c6b-b64c-969c48797978',
                    'ddsfdsfdsfef',
                    '$2y$14$4/AtTy2DGvYDx8ygAaaOS.w.Vm7k0QNID0sOCiFWymqr/Lsk3jw8q',
                )),
            )
        ;

        $this->userRepository
            ->expects($this->once())
            ->method('create')
            ->willReturn(new Success())
        ;

        $this->sessionRepository
            ->expects($this->once())
            ->method('store')
            ->willReturn(new Success())
        ;

        $response = wait($this->controller->processRequest($this->request));

        $this->assertInstanceOf(RegisterValid::class, $response);
    }
}
