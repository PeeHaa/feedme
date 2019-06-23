<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Controller;

use Amp\Promise;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Hash\PasswordMatches;
use PeeHaa\FeedMe\Entity\Session;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Request\LogIn as LogInRequest;
use PeeHaa\FeedMe\Request\Request;
use PeeHaa\FeedMe\Response\LogInInvalid;
use PeeHaa\FeedMe\Response\LogInValid;
use PeeHaa\FeedMe\Response\Response;
use PeeHaa\FeedMe\Storage\Session\Repository as SessionRepository;
use PeeHaa\FeedMe\Storage\User\Repository as UserRepository;
use function Amp\call;
use function PeeHaa\FeedMe\generateUuid;

final class LogIn implements RequestHandler
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
        /** @var LogInRequest $request */
        return call(function () use ($request) {
            /** @var User|null $user */
            $user = yield $this->userRepository->getByEmailAddress($request->getUsername());

            if ($user === null) {
                return new LogInInvalid($request->getId());
            }

            /** @var Result $passwordValidationResult */
            $passwordValidationResult = yield (new PasswordMatches($user->getHash()))->validate($request->getPassword());

            if (!$passwordValidationResult->isValid()) {
                return new LogInInvalid($request->getId());
            }

            $session = new Session(
                generateUuid(),
                $request->getClient()->getId(),
                $user->getId(),
                generateUuid(),
                (new \DateTimeImmutable())->add(new \DateInterval('PT5M')),
            );

            yield $this->sessionRepository->store($session);

            return new LogInValid($request->getId(), $user, $session);
        });
    }
}
