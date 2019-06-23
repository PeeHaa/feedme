<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Request;

use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Request\Register;
use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase
{
    /** @var Register */
    private $request;

    public function setUp(): void
    {
        $this->request = new Register(new Client(1), 'TheId', 'TheType', 'TheUsername', 'ThePassword', 'ThePassword2');
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

    public function testGetPassword2(): void
    {
        $this->assertSame('ThePassword2', $this->request->getPassword2());
    }

    public function testFromArrayBuildsCorrectInstance(): void
    {
        $requestData = [
            'id'   => 'TheId',
            'type' => 'TheType',
            'data' => [
                'username'  => 'TheUsername',
                'password'  => 'ThePassword',
                'password2' => 'ThePassword2',
            ],
        ];

        /** @var Register $request */
        $request = Register::fromArray($requestData, new Client(1));

        $this->assertInstanceOf(Register::class, $request);

        $this->assertSame('TheId', $request->getId());
        $this->assertSame('TheType', $request->getType());
        $this->assertSame('TheUsername', $request->getUsername());
        $this->assertSame('ThePassword', $request->getPassword());
        $this->assertSame('ThePassword2', $request->getPassword2());
    }
}
