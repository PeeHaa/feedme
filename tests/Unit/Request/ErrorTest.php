<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Request;

use HarmonyIO\Validation\Result\Error as ValidationError;
use HarmonyIO\Validation\Result\Parameter;
use PeeHaa\FeedMe\Exception\Exception;
use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Request\Error;
use PHPUnit\Framework\TestCase;

class ErrorTest extends TestCase
{
    public function testBuildFromArrayThrowsException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Cannot build from JSON');

        Error::fromArray([]);
    }

    public function testConstructorSetsId(): void
    {
        $request = new Error('TheId', new ValidationError('Something.Wrong'), new Client(1));

        $this->assertSame('TheId', $request->getId());
    }

    public function testConstructorSetsType(): void
    {
        $request = new Error('TheId', new ValidationError('Something.Wrong'), new Client(1));

        $this->assertSame('Error', $request->getType());
    }

    public function testGetErrors(): void
    {
        $validationResult = new ValidationError('Something.Wrong', new Parameter('Grouped', [
            'username' => [new ValidationError('Username.Unique')],
        ]));

        $request = new Error('TheId', $validationResult, new Client(1));

        $this->assertArrayHasKey('username', $request->getErrors());
    }
}
