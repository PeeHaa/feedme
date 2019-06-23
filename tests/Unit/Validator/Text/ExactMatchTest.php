<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Validator\Text;

use HarmonyIO\Validation\Result\Result;
use PeeHaa\FeedMe\Validator\Text\ExactMatch;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class ExactMatchTest extends TestCase
{
    public function testValidateReturnsFailureOnNonString(): void
    {
        /** @var Result $result */
        $result = wait((new ExactMatch('FooBar'))->validate(new \DateTimeImmutable()));

        $this->assertFalse($result->isValid());
    }

    public function testValidateReturnsFailureWhenStringDoesNotMatch(): void
    {
        /** @var Result $result */
        $result = wait((new ExactMatch('FooBar'))->validate('FooBa'));

        $this->assertFalse($result->isValid());
    }

    public function testValidateSucceedsOnValidString(): void
    {
        /** @var Result $result */
        $result = wait((new ExactMatch('FooBar'))->validate('FooBar'));

        $this->assertTrue($result->isValid());
    }
}
