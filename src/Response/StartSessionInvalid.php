<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Response;

final class StartSessionInvalid implements Response
{
    public function toJson(): string
    {
        return json_encode([
            'requestId' => 'StartSession',
            'status'    => 401,
        ], JSON_THROW_ON_ERROR);
    }
}
