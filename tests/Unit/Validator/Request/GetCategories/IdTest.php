<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Validator\Request\GetCategories;

use HarmonyIO\Validation\Result\Result;
use PeeHaa\FeedMe\Validator\Request\GetCategories\Id;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class IdTest extends TestCase
{
    public function testValidateReturnsFailureWhenNotAValidUuid4String(): void
    {
        /** @var Result $result */
        $result = wait((new Id())->validate(['id' => 'invalid-uuid4-string']));

        $this->assertFalse($result->isValid());
    }

    public function testValidateReturnsSuccessWhenAValidUuid4String(): void
    {
        /** @var Result $result */
        $result = wait((new Id())->validate(['id' => 'e77290ea-d29e-4582-9670-03afdb4bf0e7']));

        $this->assertTrue($result->isValid());
    }
}
