<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Exception;

use PeeHaa\FeedMe\Exception\ControllerNotFound;
use PHPUnit\Framework\TestCase;

class ControllerNotFoundTest extends TestCase
{
    public function testExceptionThrowsFormattedMessage(): void
    {
        $this->expectException(ControllerNotFound::class);
        $this->expectExceptionMessage('Controller Foo\Bar could not be found');

        throw new ControllerNotFound('Foo\Bar');
    }
}
