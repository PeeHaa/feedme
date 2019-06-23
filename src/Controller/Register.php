<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Controller;

use Amp\Promise;
use PeeHaa\FeedMe\Entity\NewUser;
use PeeHaa\FeedMe\Entity\Session;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Request\Register as RegisterRequest;
use PeeHaa\FeedMe\Request\Request;
use PeeHaa\FeedMe\Response\Error;
use PeeHaa\FeedMe\Response\RegisterValid;
use PeeHaa\FeedMe\Response\Response;
use PeeHaa\FeedMe\Storage\Session\Repository as SessionRepository;
use PeeHaa\FeedMe\Storage\User\Repository as UserRepository;
use function Amp\call;
use function PeeHaa\FeedMe\generateUuid;

final class Register implements RequestHandler
{
    /** @var UserRepository */
    private $userRepository;

    /** @var SessionRepository */
    private $sessionRepository;

    public function __construct(UserRepository $userRepository, SessionRepository $sessionRepository)
    {
        $this->userRepository    = $userRepository;
        $this->sessionRepository = $sessionRepository;
    }

    /**
     * @return Promise<Response>
     */
    public function processRequest(Request $request): Promise
    {
        /** @var RegisterRequest $request */
        return call(function () use ($request) {
            $newUser = new NewUser(generateUuid(), trim($request->getUsername()), $request->getPassword());

            $user = yield $this->userRepository->getByEmailAddress($newUser->getUsername());

            if ($user !== null) {
                return new Error($request->getId(), [
                    'username' => 'Username.Unique',
                ]);
            }

            yield $this->userRepository->create($newUser);

            /** @var User $user */
            $user = yield $this->userRepository->getByEmailAddress($newUser->getUsername());

            $session = new Session(
                generateUuid(),
                $request->getClient()->getId(),
                $user->getId(),
                generateUuid(),
                (new \DateTimeImmutable())->add(new \DateInterval('PT5M')),
            );

            yield $this->sessionRepository->store($session);

            return new RegisterValid($request->getId(), $user, $session);
        });
    }
}
