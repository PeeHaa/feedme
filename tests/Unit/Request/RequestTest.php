<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Request;

use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Request\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /** @var Request */
    private $request;

    public function setUp(): void
    {
        $this->request = new class extends Request
        {
            public function __construct()
            {
                parent::__construct('TheId', 'TheType', new Client(1));
            }

            /**
             * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
             *
             * @param array<mixed> $json
             */
            public static function fromArray(array $json): Request
            {
                // phpcs:enable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
                return new self();
            }
        };
    }

    public function testGetId(): void
    {
        $this->assertSame('TheId', $this->request->getId());
    }

    public function testGetType(): void
    {
        $this->assertSame('TheType', $this->request->getType());
    }
}
