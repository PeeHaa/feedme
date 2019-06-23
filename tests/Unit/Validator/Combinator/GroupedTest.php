<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Validator\Combinator;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use PeeHaa\FeedMe\Validator\Combinator\Grouped;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

class GroupedTest extends TestCase
{
    public function testConstructorThrowsOnInvalidRuleOfTypeScalar(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(
            'Rules may only contain instances of HarmonyIO\Validation\Rule\Rule, string given for rule foo',
        );

        new Grouped(['foo' => 'bar']);
    }

    public function testConstructorThrowsOnInvalidRuleOfTypeObject(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(
            'Rules may only contain instances of HarmonyIO\Validation\Rule\Rule, DateTimeImmutable given for rule foo',
        );

        new Grouped(['foo' => new \DateTimeImmutable()]);
    }

    public function testValidateReturnsSuccessWhenThereAreNoValidationErrors(): void
    {
        $rule = $this->createMock(Rule::class);

        $rule
            ->expects($this->once())
            ->method('validate')
            ->willReturn(succeed())
            ->with('value')
        ;

        /** @var Result $result */
        $result = wait((new Grouped(['foo' => $rule]))->validate('value'));

        $this->assertTrue($result->isValid());
    }

    public function testValidateReturnsFailureWhenThereAreValidationErrors(): void
    {
        $rule = $this->createMock(Rule::class);

        $rule
            ->expects($this->once())
            ->method('validate')
            ->willReturn(fail('Some validation error'))
            ->with('value')
        ;

        /** @var Result $result */
        $result = wait((new Grouped(['foo' => $rule]))->validate('value'));

        $this->assertFalse($result->isValid());
    }

    public function testValidateReturnsFailureWhenThereAreValidationErrorsEvenWhenFirstResultIsValid(): void
    {
        $validRule = $this->createMock(Rule::class);
        $invalidRule = $this->createMock(Rule::class);

        $validRule
            ->expects($this->once())
            ->method('validate')
            ->willReturn(succeed())
            ->with('value')
        ;

        $invalidRule
            ->expects($this->once())
            ->method('validate')
            ->willReturn(fail('Some validation error'))
            ->with('value')
        ;

        /** @var Result $result */
        $result = wait((new Grouped(['foo' => $validRule, 'bar' => $invalidRule]))->validate('value'));

        $this->assertFalse($result->isValid());
    }
}
