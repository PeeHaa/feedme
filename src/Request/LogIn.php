<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Request;

use PeeHaa\FeedMe\Http\WebSocket\Client;

final class LogIn extends Request
{
    /** @var string */
    private $username;

    /** @var string */
    private $password;

    public function __construct(Client $client, string $id, string $type, string $username, string $password)
    {
        parent::__construct($id, $type, $client);

        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @param array<mixed> $requestData
     */
    public static function fromArray(array $requestData, Client $client): Request
    {
        return new self(
            $client,
            $requestData['id'],
            $requestData['type'],
            $requestData['data']['username'],
            $requestData['data']['password'],
        );
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
