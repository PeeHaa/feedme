<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Validator\Request\SubscribeToAll;

use HarmonyIO\Validation\Result\Result;
use PeeHaa\FeedMe\Validator\Request\SubScribeToAll\Type;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class TypeTest extends TestCase
{
    public function testValidateReturnsFailureWhenTypeDoesNotMatch(): void
    {
        /** @var Result $result */
        $result = wait((new Type())->validate(['type' => 'NotSubscribeToAll']));

        $this->assertFalse($result->isValid());
    }

    public function testValidateReturnsSuccessWhenValid(): void
    {
        /** @var Result $result */
        $result = wait((new Type())->validate(['type' => 'SubscribeToAll']));

        $this->assertTrue($result->isValid());
    }
}
