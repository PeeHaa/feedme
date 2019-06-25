<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Request;

use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Request\AuthenticatedRequest;
use PHPUnit\Framework\TestCase;

class AuthenticatedRequestTest extends TestCase
{
    /** @var AuthenticatedRequest */
    private $request;

    public function setUp(): void
    {
        $this->request = new class extends AuthenticatedRequest
        {
            public function __construct()
            {
                parent::__construct('id', 'type', new Client(1), new User('id', 'username', 'hash'));
            }

            /**
             * @param array<mixed> $json
             */
            public static function fromArray(array $json, Client $client, User $user): AuthenticatedRequest
            {
                throw new \Exception();
            }
        };
    }

    public function testGetId(): void
    {
        $this->assertSame('id', $this->request->getId());
    }

    public function testGetType(): void
    {
        $this->assertSame('type', $this->request->getType());
    }

    public function testGetClient(): void
    {
        $this->assertInstanceOf(Client::class, $this->request->getClient());
    }

    public function testGetUser(): void
    {
        $this->assertInstanceOf(User::class, $this->request->getUser());
    }
}
