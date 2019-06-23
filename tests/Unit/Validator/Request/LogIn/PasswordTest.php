<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Validator\Request\LogIn;

use HarmonyIO\Validation\Result\Result;
use PeeHaa\FeedMe\Validator\Request\LogIn\Password;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class PasswordTest extends TestCase
{
    public function testValidateReturnsFailureWhenTooShort(): void
    {
        /** @var Result $result */
        $result = wait((new Password())->validate(['data' => ['password' => '12345']]));

        $this->assertFalse($result->isValid());
    }

    public function testValidateReturnsSuccessWhenValid(): void
    {
        /** @var Result $result */
        $result = wait((new Password())->validate(['data' => ['password' => '123456']]));

        $this->assertTrue($result->isValid());
    }
}
