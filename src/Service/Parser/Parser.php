<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Service\Parser;

use PeeHaa\FeedMe\Collection\Articles;

interface Parser
{
    public function parse(string $feedId, string $source): Articles;
}
