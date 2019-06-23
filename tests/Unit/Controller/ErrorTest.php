<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Controller;

use HarmonyIO\Validation\Result\Error as ValidationError;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Result\Result;
use PeeHaa\FeedMe\Controller\Error;
use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Request\Error as Request;
use PeeHaa\FeedMe\Response\Response;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class ErrorTest extends TestCase
{
    /** @var Request */
    private $request;

    public function setUp(): void
    {
        $validationResult = new Result(false, new ValidationError('Something.Wrong', new Parameter('Grouped', [
            'username' => [new ValidationError('Username.Unique')],
        ])));

        $this->request = new Request('TheId', $validationResult->getFirstError(), new Client(1));
    }

    public function testProcessRequestReturnsTheRequestId(): void
    {
        /** @var Response $response */
        $response = wait((new Error())->processRequest($this->request));

        $responseData = json_decode($response->toJson(), true);

        $this->assertArrayHasKey('requestId', $responseData);
        $this->assertSame('TheId', $responseData['requestId']);
    }

    public function testProcessRequestReturnsTheCorrectStatusCode(): void
    {
        /** @var Response $response */
        $response = wait((new Error())->processRequest($this->request));

        $responseData = json_decode($response->toJson(), true);

        $this->assertArrayHasKey('status', $responseData);
        $this->assertSame(401, $responseData['status']);
    }

    public function testProcessRequestReturnsTheErrors(): void
    {
        /** @var Response $response */
        $response = wait((new Error())->processRequest($this->request));

        $responseData = json_decode($response->toJson(), true);

        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey('username', $responseData['errors']);
        $this->assertSame('Username.Unique', $responseData['errors']['username']);
    }
}
