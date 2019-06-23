<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Controller;

use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Server\Router;
use Amp\Http\Server\Session\Session as UserSession;
use Amp\Http\Status;
use Amp\Promise;
use PeeHaa\FeedMe\Entity\Session;
use PeeHaa\FeedMe\Response\StartSessionInvalid;
use PeeHaa\FeedMe\Response\StartSessionValid;
use PeeHaa\FeedMe\Storage\Session\Repository as SessionRepository;
use function Amp\call;

final class StartSession implements RequestHandler
{
    /** @var SessionRepository */
    private $sessionRepository;

    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    /**
     * @return Promise<Response>
     */
    public function handleRequest(Request $request): Promise
    {
        return call(function () use ($request) {
            $args = $request->getAttribute(Router::class);

            /** @var Session|null $session */
            $session = yield $this->sessionRepository->get($args['id'], $args['userId']);

            if (!$session) {
                return new Response(
                    Status::UNAUTHORIZED,
                    ['content-type' => 'application/json; charset=utf-8'],
                    (new StartSessionInvalid())->toJson(),
                );
            }

            /** @var UserSession $userSession */
            $userSession = $request->getAttribute('_session');

            yield $userSession->open();

            $userSession->set('userId', $session->getUserId());

            yield $userSession->save();

            yield $this->sessionRepository->delete($session);

            return new Response(
                Status::OK,
                ['content-type' => 'application/json; charset=utf-8'],
                (new StartSessionValid())->toJson(),
            );
        });
    }
}
