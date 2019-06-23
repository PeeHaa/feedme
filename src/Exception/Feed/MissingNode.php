<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Exception\Feed;

class MissingNode extends BrokenSource
{
    public function __construct(string $feedId, string $nodeName)
    {
        parent::__construct(
            sprintf('Source of %s is missing the %s node', $feedId, $nodeName),
        );
    }
}
