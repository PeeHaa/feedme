<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit;

use Amp\Success;
use Auryn\Injector;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use PeeHaa\FeedMe\Controller\Factory as ControllerFactory;
use PeeHaa\FeedMe\Controller\LogIn;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Exception\InvalidRequest;
use PeeHaa\FeedMe\FrontController;
use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Request\Factory as RequestFactory;
use PeeHaa\FeedMe\Response\Error;
use PeeHaa\FeedMe\Response\LogInValid;
use PeeHaa\FeedMe\Storage\Session\Repository as SessionRepository;
use PeeHaa\FeedMe\Storage\User\Repository as UserRepository;
use PeeHaa\FeedMe\Validator\Combinator\Grouped;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;
use function HarmonyIO\Validation\fail;

class FrontControllerTest extends TestCase
{
    public function testHandleRequestThrowsOnInvalidJsonRequest(): void
    {
        $auryn             = $this->createMock(Injector::class);
        $controllerFactory = new ControllerFactory($auryn);
        $requestFactory    = new RequestFactory();

        $this->expectException(InvalidRequest::class);

        wait((new FrontController($auryn, $requestFactory, $controllerFactory))->handleRequest(
            '{"foo":',
            new Client(1),
        ));
    }

    public function testHandleRequestThrowsOnMissingRequestId(): void
    {
        $auryn             = $this->createMock(Injector::class);
        $controllerFactory = new ControllerFactory($auryn);
        $requestFactory    = new RequestFactory();

        $this->expectException(InvalidRequest::class);

        wait((new FrontController($auryn, $requestFactory, $controllerFactory))->handleRequest(
            '{"type":"LogIn"}',
            new Client(1),
        ));
    }

    public function testHandleRequestThrowsOnMissingRequestType(): void
    {
        $auryn             = $this->createMock(Injector::class);
        $controllerFactory = new ControllerFactory($auryn);
        $requestFactory    = new RequestFactory();

        $this->expectException(InvalidRequest::class);

        wait((new FrontController(
            $auryn,
            $requestFactory,
            $controllerFactory,
        ))->handleRequest(
            '{"id":"097b6970-6919-4efc-a1f0-439be0bfc25c"}',
            new Client(1),
        ));
    }

    public function testHandleRequestThrowsOnRequestTypeWithBackslash(): void
    {
        $auryn             = $this->createMock(Injector::class);
        $controllerFactory = new ControllerFactory($auryn);
        $requestFactory    = new RequestFactory();

        $this->expectException(InvalidRequest::class);

        wait((new FrontController(
            $auryn,
            $requestFactory,
            $controllerFactory,
        ))->handleRequest(
            '{"id":"097b6970-6919-4efc-a1f0-439be0bfc25c", "type": "Foo\\Bar"}',
            new Client(1),
        ));
    }

    public function testHandleRequestReturnsErrorResponseOnInvalidRequest(): void
    {
        $auryn             = $this->createMock(Injector::class);
        $controllerFactory = new ControllerFactory($auryn);
        $requestFactory    = new RequestFactory();

        $rule = $this->createMock(Rule::class);

        $rule
            ->expects($this->once())
            ->method('validate')
            ->willReturn(fail('Some validation error'))
            ->with('value')
        ;

        $auryn
            ->expects($this->once())
            ->method('execute')
            ->willReturn((new Grouped(['foo' => $rule]))->validate('value'))
            ->with('PeeHaa\FeedMe\Validator\Request\LogIn::validate')
        ;

        $response = wait((new FrontController(
            $auryn,
            $requestFactory,
            $controllerFactory,
        ))->handleRequest(
            '{"id":"097b6970-6919-4efc-a1f0-439be0bfc25c", "type": "LogIn"}',
            new Client(1),
        ));

        $this->assertInstanceOf(Error::class, $response);
    }

    public function testHandleRequestReturnsErrorResponseOnValidRequest(): void
    {
        $auryn             = $this->createMock(Injector::class);
        $controllerFactory = new ControllerFactory($auryn);
        $requestFactory    = new RequestFactory();

        $auryn
            ->expects($this->once())
            ->method('execute')
            ->willReturn(new Success(new Result(true)))
            ->with('PeeHaa\FeedMe\Validator\Request\LogIn::validate')
        ;

        $userRepository    = $this->createMock(UserRepository::class);
        $sessionRepository = $this->createMock(SessionRepository::class);

        $userRepository
            ->method('getByEmailAddress')
            ->willReturn(
                new Success(
                    new User('TheId', 'test@example.com', '$2y$14$GSF/GosuIlmZwpuMkFndcu6pJJ0rrpWgLRN/iIUeayeTFX2O5w80O'),
                ),
            )
        ;

        $sessionRepository
            ->method('store')
            ->willReturn(new Success())
        ;

        $auryn
            ->expects($this->once())
            ->method('make')
            ->willReturn(new LogIn($userRepository, $sessionRepository))
        ;

        $response = wait((new FrontController(
            $auryn,
            $requestFactory,
            $controllerFactory,
        ))->handleRequest(
            '{"id":"097b6970-6919-4efc-a1f0-439be0bfc25c", "type": "LogIn", "data": {"username": "test@example.com", "password": "test123"}}',
            new Client(1),
        ));

        $this->assertInstanceOf(LogInValid::class, $response);
    }
}
