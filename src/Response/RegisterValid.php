<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Response;

use PeeHaa\FeedMe\Entity\Session;
use PeeHaa\FeedMe\Entity\User;

final class RegisterValid implements Response
{
    /** @var string */
    private $requestId;

    /** @var User */
    private $user;

    /** @var Session */
    private $session;

    public function __construct(string $requestId, User $user, Session $session)
    {
        $this->requestId = $requestId;
        $this->user      = $user;
        $this->session   = $session;
    }

    public function toJson(): string
    {
        return json_encode([
            'requestId' => $this->requestId,
            'status'    => 200,
            'data'      => [
                'user' => [
                    'id'       => $this->user->getId(),
                    'username' => $this->user->getUsername(),
                ],
                'session' => [
                    'id'     => $this->session->getId(),
                    'userId' => $this->session->getUserId(),
                    'token'  => $this->session->getToken(),
                ],
            ],
        ], JSON_THROW_ON_ERROR);
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
