<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Exception\Feed;

use PeeHaa\FeedMe\Exception\Feed\MissingNode;
use PHPUnit\Framework\TestCase;

class MissingNodeTest extends TestCase
{
    public function testConstructorFormatsMessageCorrectly(): void
    {
        $this->expectException(MissingNode::class);
        $this->expectExceptionMessage('Source of TheFeedId is missing the something node');

        throw new MissingNode('TheFeedId', 'something');
    }
}
