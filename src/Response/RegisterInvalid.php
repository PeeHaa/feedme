<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Response;

final class RegisterInvalid implements Response
{
    /** @var string */
    private $requestId;

    /** @var array<string,string> */
    private $errors;

    /**
     * @param array<string,string> $errors
     */
    public function __construct(string $requestId, array $errors)
    {
        $this->requestId = $requestId;
        $this->errors    = $errors;
    }

    public function toJson(): string
    {
        return json_encode([
            'requestId' => $this->requestId,
            'status'    => 406,
            'errors'    => $this->errors,
        ], JSON_THROW_ON_ERROR);
    }
}
