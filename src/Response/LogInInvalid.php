<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Response;

final class LogInInvalid implements Response
{
    /** @var string */
    private $requestId;

    public function __construct(string $requestId)
    {
        $this->requestId = $requestId;
    }

    public function toJson(): string
    {
        return json_encode([
            'requestId' => $this->requestId,
            'status'    => 401,
        ], JSON_THROW_ON_ERROR);
    }
}
