<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Request;

use PeeHaa\FeedMe\Exception\RequestNotFound;
use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Request\Factory;
use PeeHaa\FeedMe\Request\LogIn;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testBuildFromArrayThrowsWhenRequestDoesNotExist(): void
    {
        $this->expectException(RequestNotFound::class);
        $this->expectExceptionMessage(
            'Request PeeHaa\FeedMe\Request\UnknownRequest could not be found',
        );

        (new Factory())->buildFromArray(new Client(1), ['type' => 'UnknownRequest']);
    }

    public function testBuildFromArrayBuildsRequest(): void
    {
        $requestData = [
            'id'   => 'TheId',
            'type' => 'LogIn',
            'data' => [
                'username' => 'TheUsername',
                'password' => 'ThePassword',
            ],
        ];

        $this->assertInstanceOf(LogIn::class, (new Factory())->buildFromArray(new Client(1), $requestData));
    }
}
