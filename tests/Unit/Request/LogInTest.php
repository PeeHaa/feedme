<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Request;

use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Request\LogIn;
use PHPUnit\Framework\TestCase;

class LogInTest extends TestCase
{
    /** @var LogIn */
    private $request;

    public function setUp(): void
    {
        $this->request = new LogIn(new Client(1), 'TheId', 'TheType', 'TheUsername', 'ThePassword');
    }

    public function testGetId(): void
    {
        $this->assertSame('TheId', $this->request->getId());
    }

    public function testGetType(): void
    {
        $this->assertSame('TheType', $this->request->getType());
    }

    public function testGetUsername(): void
    {
        $this->assertSame('TheUsername', $this->request->getUsername());
    }

    public function testGetPassword(): void
    {
        $this->assertSame('ThePassword', $this->request->getPassword());
    }

    public function testFromArrayBuildsCorrectInstance(): void
    {
        $requestData = [
            'id'   => 'TheId',
            'type' => 'TheType',
            'data' => [
                'username' => 'TheUsername',
                'password' => 'ThePassword',
            ],
        ];

        /** @var LogIn $request */
        $request = LogIn::fromArray($requestData, new Client(1));

        $this->assertInstanceOf(LogIn::class, $request);

        $this->assertSame('TheId', $request->getId());
        $this->assertSame('TheType', $request->getType());
        $this->assertSame('TheUsername', $request->getUsername());
        $this->assertSame('ThePassword', $request->getPassword());
    }
}
