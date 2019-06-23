<?php declare(strict_types=1);

namespace PeeHaa\FeedMe;

use Amp\Artax\Client as HttpClient;
use Amp\Artax\DefaultClient;
use Amp\Http\Cookie\CookieAttributes;
use Amp\Http\Server\Router;
use Amp\Http\Server\Server;
use Amp\Http\Server\Session\RedisStorage;
use Amp\Http\Server\Session\SessionMiddleware;
use Amp\Http\Server\Session\Storage;
use Amp\Http\Server\StaticContent\DocumentRoot;
use Amp\Log\ConsoleFormatter;
use Amp\Log\StreamHandler;
use Amp\Postgres\Link;
use Amp\Redis\Client as RedisClient;
use Auryn\Injector;
use Kelunik\RedisMutex\Mutex;
use Monolog\Logger;
use PeeHaa\FeedMe\Configuration\BindAddress;
use PeeHaa\FeedMe\Configuration\Configuration;
use PeeHaa\FeedMe\Controller\Index;
use PeeHaa\FeedMe\Controller\ReadArticle;
use PeeHaa\FeedMe\Controller\StartSession;
use PeeHaa\FeedMe\Event\NewArticleManager;
use PeeHaa\FeedMe\Http\WebSocket;
use PeeHaa\FeedMe\Http\WebSocket\Subscriptions;
use PeeHaa\FeedMe\Storage\Article\PostgresqlRepository as ArticlePostgresqlRepository;
use PeeHaa\FeedMe\Storage\Article\Repository as ArticleRepository;
use PeeHaa\FeedMe\Storage\CrawlerQueue\RedisRepository as CrawlerQueueRedisRepository;
use PeeHaa\FeedMe\Storage\CrawlerQueue\Repository as CrawlerQueueRepository;
use PeeHaa\FeedMe\Storage\Feed\PostgresqlRepository as FeedPostgresqlRepository;
use PeeHaa\FeedMe\Storage\Feed\Repository as FeedRepository;
use PeeHaa\FeedMe\Storage\Session\PostgresqlRepository as SessionPostgresqlRepository;
use PeeHaa\FeedMe\Storage\Session\Repository as SessionRepository;
use PeeHaa\FeedMe\Storage\Subscription\PostgresqlRepository as SubscriptionPostgresqlRepository;
use PeeHaa\FeedMe\Storage\Subscription\Repository as SubscriptionRepository;
use PeeHaa\FeedMe\Storage\User\PostgresqlRepository as UserPostgresqlRepository;
use PeeHaa\FeedMe\Storage\User\Repository as UserRepository;
use PeeHaa\FeedMe\Storage\UserCategory\PostgresqlRepository as UserCategoryPostgresqlRepository;
use PeeHaa\FeedMe\Storage\UserCategory\Repository as UserCategoryRepository;
use Psr\Log\LoggerInterface;
use function Amp\ByteStream\getStdout;
use function Amp\Http\Server\Middleware\stack;
use function Amp\Postgres\pool;
use function Amp\Socket\listen;

require_once __DIR__ . '/vendor/autoload.php';

$auryn = new Injector();

$auryn->share($auryn);

$configuration = Configuration::fromArray(require __DIR__ . '/config.php');

$auryn->alias(HttpClient::class, DefaultClient::class);

$auryn->share(Link::class);
$auryn->delegate(Link::class, static function () use ($configuration) {
    return pool($configuration->getDatabase()->toConnectionConfig());
});

$auryn->share(LoggerInterface::class);
$auryn->alias(LoggerInterface::class, Logger::class);
$auryn->delegate(Logger::class, static function () {
    $logHandler = new StreamHandler(getStdout());
    $logHandler->setFormatter(new ConsoleFormatter(null, null, false, true));

    $logger = new Logger('FeedMe');
    $logger->pushHandler($logHandler);

    return $logger;
});

$auryn->define(RedisClient::class, [
    ':uri' => $configuration->getRedis()->getUrl(),
]);

$auryn->share(Subscriptions::class);

$auryn->share(NewArticleManager::class);

$auryn->alias(Storage::class, RedisStorage::class);
$auryn->define(Mutex::class, [
    ':uri' => $configuration->getRedis()->getUrl(),
]);

$auryn->delegate(CookieAttributes::class, static function () use ($configuration) {
    return CookieAttributes::default()
        ->withDomain($configuration->getWebServer()->getDomain())
        ->withPath('/')
        ->withHttpOnly()
        //->withSecure()
        ->withExpiry((new \DateTimeImmutable())->add(new \DateInterval('P1D')))
    ;
});

$auryn->define(SessionMiddleware::class, [
    ':cookieAttributes' => $auryn->make(CookieAttributes::class),
    ':cookieName'       => 'feedme_session',
    ':requestAttribute' => '_session',
]);

$auryn->share(WebSocket::class);

$auryn->delegate(Server::class, static function () use ($auryn, $configuration) {
    $router = new Router();

    $router->addRoute('GET', '/', new Index());
    $router->addRoute('GET', '/start-session/{id}/{userId}/{token}', $auryn->make(StartSession::class));
    $router->addRoute('GET', '/read/{id}', $auryn->make(ReadArticle::class));

    $router->addRoute('GET', '/ws', $auryn->make(WebSocket::class));
    $router->setFallback(new DocumentRoot(__DIR__ . '/public'));

    $sockets = array_reduce($configuration->getWebServer()->getAddresses(), static function (array $addresses, BindAddress $address) {
        $addresses[] = listen($address->toString());

        return $addresses;
    }, []);

    return new Server($sockets, stack($router, $auryn->make(SessionMiddleware::class)), $auryn->make(LoggerInterface::class));
});

$auryn->alias(CrawlerQueueRepository::class, CrawlerQueueRedisRepository::class);
$auryn->alias(FeedRepository::class, FeedPostgresqlRepository::class);
$auryn->alias(ArticleRepository::class, ArticlePostgresqlRepository::class);
$auryn->alias(UserRepository::class, UserPostgresqlRepository::class);
$auryn->alias(UserCategoryRepository::class, UserCategoryPostgresqlRepository::class);
$auryn->alias(SubscriptionRepository::class, SubscriptionPostgresqlRepository::class);
$auryn->alias(SessionRepository::class, SessionPostgresqlRepository::class);
