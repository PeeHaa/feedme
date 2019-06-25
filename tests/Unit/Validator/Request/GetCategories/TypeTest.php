<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Validator\Request\GetCategories;

use HarmonyIO\Validation\Result\Result;
use PeeHaa\FeedMe\Validator\Request\GetCategories\Type;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class TypeTest extends TestCase
{
    public function testValidateReturnsFailureWhenTypeDoesNotMatch(): void
    {
        /** @var Result $result */
        $result = wait((new Type())->validate(['type' => 'NotGetCategories']));

        $this->assertFalse($result->isValid());
    }

    public function testValidateReturnsSuccessWhenValid(): void
    {
        /** @var Result $result */
        $result = wait((new Type())->validate(['type' => 'GetCategories']));

        $this->assertTrue($result->isValid());
    }
}
