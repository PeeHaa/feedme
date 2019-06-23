<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Controller;

use Amp\ByteStream\InMemoryStream;
use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Server\Router;
use Amp\Http\Server\Session\Session as UserSession;
use Amp\Http\Status;
use Amp\Promise;
use PeeHaa\FeedMe\Entity\Article;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Http\WebSocket;
use PeeHaa\FeedMe\Storage\Article\Repository as ArticleRepository;
use PeeHaa\FeedMe\Storage\User\Repository as UserRepository;
use function Amp\call;

final class ReadArticle implements RequestHandler
{
    /** @var ArticleRepository */
    private $articleRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var WebSocket */
    private $webSocketHandler;

    public function __construct(
        ArticleRepository $articleRepository,
        UserRepository $userRepository,
        WebSocket $webSocketHandler
    ) {
        $this->articleRepository = $articleRepository;
        $this->userRepository    = $userRepository;
        $this->webSocketHandler  = $webSocketHandler;
    }

    /**
     * @return Promise<Response>
     */
    public function handleRequest(Request $request): Promise
    {
        return call(function () use ($request) {
            $args = $request->getAttribute(Router::class);

            /** @var Article|null $article */
            $article = yield $this->articleRepository->getById($args['id']);

            if (!$article) {
                // @todo: handle invalid article
            }

            /** @var UserSession $userSession */
            $userSession = $request->getAttribute('_session');

            yield $userSession->open();

            if (!$userSession->has('userId')) {
                return new Response(Status::FOUND, [
                    'location'       => $article->getUrl(),
                    'content-length' => 0,
                ], new InMemoryStream());
            }

            $userId = $userSession->get('userId');

            yield $userSession->save();

            /** @var User|null $user */
            $user = yield $this->userRepository->getById($userId);

            if (!$user) {
                return new Response(Status::FOUND, [
                    'location'       => $article->getUrl(),
                    'content-length' => 0,
                ], new InMemoryStream());
            }

            yield $this->webSocketHandler->markArticleAsReadForUser($article, $user);
            yield $this->articleRepository->markAsRead($article, $user);

            return new Response(Status::FOUND, [
                'location'       => $article->getUrl(),
                'content-length' => 0,
            ], new InMemoryStream());
        });
    }
}
