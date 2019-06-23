<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Exception;

use PeeHaa\FeedMe\Exception\RequestNotFound;
use PHPUnit\Framework\TestCase;

class RequestNotFoundTest extends TestCase
{
    public function testExceptionThrowsFormattedMessage(): void
    {
        $this->expectException(RequestNotFound::class);
        $this->expectExceptionMessage('Request Foo\Bar could not be found');

        throw new RequestNotFound('Foo\Bar');
    }
}
