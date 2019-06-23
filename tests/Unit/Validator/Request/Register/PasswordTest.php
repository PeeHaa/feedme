<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Validator\Request\Register;

use HarmonyIO\Validation\Result\Result;
use PeeHaa\FeedMe\Validator\Request\Register\Password;
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

    public function testValidateReturnsFailureWhenNoMatchWithPassword2(): void
    {
        /** @var Result $result */
        $result = wait((new Password())->validate(['data' => [
            'password'  => '123456',
            'password2' => '123457',
        ]]));

        $this->assertFalse($result->isValid());
    }

    public function testValidateReturnsSuccessWhenValid(): void
    {
        /** @var Result $result */
        $result = wait((new Password())->validate(['data' => [
            'password'  => '123456',
            'password2' => '123456',
        ]]));

        $this->assertTrue($result->isValid());
    }
}
