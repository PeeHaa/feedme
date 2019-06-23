<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Controller;

use Auryn\Injector;
use PeeHaa\FeedMe\Controller\Factory;
use PeeHaa\FeedMe\Controller\RequestHandler;
use PeeHaa\FeedMe\Exception\ControllerNotFound;
use PeeHaa\FeedMeTest\Fakes\ControllerFoundRequest;
use PeeHaa\FeedMeTest\Fakes\ControllerNotFoundRequest;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testBuildFromRequestThrowsWhenControllerDoesNotExist(): void
    {
        $auryn = $this->createMock(Injector::class);

        $this->expectException(ControllerNotFound::class);
        $this->expectExceptionMessage(
            'Controller PeeHaa\FeedMe\Controller\UnknownNamespace\UnknownController could not be found',
        );

        (new Factory($auryn))->buildFromRequest(new ControllerNotFoundRequest());
    }

    public function testBuildFromRequestBuildsController(): void
    {
        $auryn = $this->createMock(Injector::class);

        $auryn
            ->expects($this->once())
            ->method('make')
            ->willReturn($this->createMock(RequestHandler::class))
        ;

        (new Factory($auryn))->buildFromRequest(new ControllerFoundRequest());
    }
}
