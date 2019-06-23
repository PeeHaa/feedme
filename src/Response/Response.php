<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Response;

interface Response
{
    public function toJson(): string;
}
