<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Validator\Request\LogIn;

use HarmonyIO\Validation\Result\Result;
use PeeHaa\FeedMe\Validator\Request\LogIn\Username;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class UsernameTest extends TestCase
{
    public function testValidateReturnsFailureWhenNotAnEmailAddress(): void
    {
        /** @var Result $result */
        $result = wait((new Username())->validate(['data' => ['username' => 'not an email address']]));

        $this->assertFalse($result->isValid());
    }

    public function testValidateReturnsSuccessWhenValid(): void
    {
        /** @var Result $result */
        $result = wait((new Username())->validate(['data' => ['username' => 'test@example.com']]));

        $this->assertTrue($result->isValid());
    }
}
