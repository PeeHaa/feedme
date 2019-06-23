<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Request;

use HarmonyIO\Validation\Result\Error as ValidationError;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Exception\Exception;
use PeeHaa\FeedMe\Http\WebSocket\Client;

final class Error extends Request
{
    /** @var ValidationError */
    private $error;

    public function __construct(string $id, ValidationError $error, Client $client)
    {
        parent::__construct($id, 'Error', $client);

        $this->error = $error;
    }

    /**
     * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     *
     * @param array<mixed> $requestData
     */
    public static function fromArray(array $requestData, ?User $user = null): Request
    {
        // phpcs:enable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
        throw new Exception('Cannot build from JSON');
    }

    /**
     * @return array<string,array<ValidationError>>
     */
    public function getErrors(): array
    {
        return $this->error->getParameters()[0]->getValue();
    }
}
