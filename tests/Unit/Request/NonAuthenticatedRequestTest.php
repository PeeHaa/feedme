<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Request;

use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Request\NonAuthenticatedRequest;
use PHPUnit\Framework\TestCase;

class NonAuthenticatedRequestTest extends TestCase
{
    /** @var NonAuthenticatedRequest */
    private $request;

    public function setUp(): void
    {
        $this->request = new class extends NonAuthenticatedRequest
        {
            public function __construct()
            {
                parent::__construct('id', 'type', new Client(1));
            }

            /**
             * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
             *
             * @param array<mixed> $json
             */
            public static function fromArray(array $json, Client $client): NonAuthenticatedRequest
            {
                //phpcs:enable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
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
}
