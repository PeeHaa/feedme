<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Request;

use PeeHaa\FeedMe\Http\WebSocket\Client;

final class Register extends Request
{
    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $password2;

    public function __construct(Client $client, string $id, string $type, string $username, string $password, string $password2)
    {
        parent::__construct($id, $type, $client);

        $this->username  = $username;
        $this->password  = $password;
        $this->password2 = $password2;
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
            $requestData['data']['password2'],
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

    public function getPassword2(): string
    {
        return $this->password2;
    }
}
