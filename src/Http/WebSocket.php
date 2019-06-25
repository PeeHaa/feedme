<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Http;

use Amp\Http\Server\Request;
use Amp\Http\Server\Response;
use Amp\Http\Server\Server;
use Amp\Http\Server\Websocket\Message;
use Amp\Http\Server\Websocket\Websocket as WebSocketServer;
use Amp\Promise;
use PeeHaa\FeedMe\Configuration\WebServer as WebServerConfiguration;
use PeeHaa\FeedMe\Entity\Article;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Event\NewArticleManager;
use PeeHaa\FeedMe\FrontController;
use PeeHaa\FeedMe\Http\WebSocket\Clients;
use PeeHaa\FeedMe\Http\WebSocket\Subscriptions;
use PeeHaa\FeedMe\Response\LogInValid;
use PeeHaa\FeedMe\Response\NewArticle;
use PeeHaa\FeedMe\Response\ReadArticle;
use PeeHaa\FeedMe\Response\RegisterValid;
use PeeHaa\FeedMe\Response\Response as SocketResponse;
use Psr\Log\LoggerInterface;
use function Amp\call;

class WebSocket extends WebSocketServer
{
    /** @var FrontController */
    private $frontController;

    /** @var LoggerInterface */
    private $logger;

    /** @var Clients */
    private $clients;

    /** @var Subscriptions */
    private $subscriptions;

    /** @var NewArticleManager */
    private $newArticleManager;

    /** @var WebServerConfiguration */
    private $webServerConfiguration;

    public function __construct(
        FrontController $frontController,
        Subscriptions $subscriptions,
        NewArticleManager $newArticleManager,
        WebServerConfiguration $webServerConfiguration,
        LoggerInterface $logger
    ) {
        $this->frontController        = $frontController;
        $this->subscriptions          = $subscriptions;
        $this->newArticleManager      = $newArticleManager;
        $this->webServerConfiguration = $webServerConfiguration;
        $this->logger                 = $logger;

        parent::__construct();
    }

    /**
     * @return Promise<null>
     */
    public function onStart(Server $server): Promise
    {
        $this->logger->info('WebSocket server started');

        $this->clients = new Clients();

        $this->newArticleManager->listen(function (Article $article): Promise {
            return call(function () use ($article): void {
                $clientIds = $this->subscriptions->getClientIdsByFeedId($article->getFeedId());

                if (!$clientIds) {
                    return;
                }

                $this->multicast((new NewArticle($article))->toJson(), $clientIds);
            });
        });

        return parent::onStart($server);
    }

    /**
     * @return Promise<int>
     */
    public function onStop(Server $server): Promise
    {
        $this->logger->info('WebSocket server stopped');

        return parent::onStop($server);
    }

    public function onHandshake(Request $request, Response $response): Response
    {
        if (!in_array($request->getHeader('origin'), [
            sprintf(
                'http://%s:%d',
                $this->webServerConfiguration->getDomain(),
                $this->webServerConfiguration->getPort(),
            ),
        ], true)) {
            $response->setStatus(403);
        }

        return $response;
    }

    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function onOpen(int $clientId, Request $request): void
    {
        $this->logger->info('New WebSocket client connected');

        $this->clients->add($clientId);
    }

    /**
     * @return Promise<null>
     */
    public function onData(int $clientId, Message $message): Promise
    {
        return call(function () use ($clientId, $message) {
            if ($this->clients->getById($clientId) === null) {
                return;
            }

            /** @var SocketResponse $response */
            $response = yield $this->frontController->handleRequest(
                yield $message->read(),
                $this->clients->getById($clientId),
                $this->clients->getById($clientId)->getUser(),
            );

            if ($response instanceof LogInValid || $response instanceof RegisterValid) {
                $this->clients->add($clientId, $response->getUser());
            }

            $this->send($response->toJson(), $clientId);
        });
    }

    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
    public function onClose(int $clientId, int $code, string $reason): void
    {
        if ($this->clients->getById($clientId) === null) {
            return;
        }

        $this->subscriptions->removeByClient($this->clients->getById($clientId));

        $this->clients->removeById($clientId);

        $this->logger->info('WebSocket client disconnected');
    }

    /**
     * @return Promise<int>
     */
    public function markArticleAsReadForUser(Article $article, User $user): Promise
    {
        return $this->multicast(
            (new ReadArticle($article))->toJson(),
            $this->clients->getClientIdsByUserId($user->getId()),
        );
    }
}
