<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Validator\Request\Register;

use HarmonyIO\Validation\Result\Result;
use PeeHaa\FeedMe\Validator\Request\Register\Type;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class TypeTest extends TestCase
{
    public function testValidateReturnsFailureWhenTypeDoesNotMatch(): void
    {
        /** @var Result $result */
        $result = wait((new Type())->validate(['type' => 'NotRegister']));

        $this->assertFalse($result->isValid());
    }

    public function testValidateReturnsSuccessWhenValid(): void
    {
        /** @var Result $result */
        $result = wait((new Type())->validate(['type' => 'Register']));

        $this->assertTrue($result->isValid());
    }
}
