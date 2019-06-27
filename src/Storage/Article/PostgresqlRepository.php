<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Storage\Article;

use Amp\Postgres\Link;
use Amp\Postgres\ResultSet;
use Amp\Promise;
use Amp\Sql\Statement;
use PeeHaa\FeedMe\Collection\Articles;
use PeeHaa\FeedMe\Collection\UserArticles;
use PeeHaa\FeedMe\Entity\Article;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Entity\UserArticle;
use function Amp\call;
use function PeeHaa\FeedMe\generateUuid;

final class PostgresqlRepository implements Repository
{
    /** @var Link */
    private $dbConnection;

    public function __construct(Link $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    /**
     * @return Promise<Articles>
     */
    public function storeNewArticles(Articles $articles): Promise
    {
        return call(function () use ($articles) {
            $newArticles = new Articles();

            foreach ($articles as $article) {
                if (!yield $this->storeNewArticle($article)) {
                    continue;
                }

                $newArticles->add($article);
            }

            return $newArticles;
        });
    }

    /**
     * @return Promise<bool>
     */
    private function storeNewArticle(Article $article): Promise
    {
        return call(function () use ($article) {
            // phpcs:ignore SlevomatCodingStandard.PHP.UselessParentheses.UselessParentheses
            if ((yield $this->articleExists($article)) === true) {
                return false;
            }

            yield $this->addArticle($article);

            return true;
        });
    }

    /**
     * @return Promise<bool>
     */
    private function articleExists(Article $article): Promise
    {
        return call(function () use ($article) {
            $query = 'SELECT COUNT(id) FROM articles WHERE source_id = ? AND feed_id = ?';

            /** @var Statement $statement */
            $statement = yield $this->dbConnection->prepare($query);

            /** @var ResultSet $result */
            $result = yield $statement->execute([
                $article->getSourceId(),
                $article->getFeedId(),
            ]);

            yield $result->advance();

            return (bool) $result->getCurrent()['count'];
        });
    }

    /**
     * @return Promise<null>
     */
    private function addArticle(Article $article): Promise
    {
        return call(function () use ($article) {
            $query = '
                INSERT INTO articles
                    (id, source_id, feed_id, url, source, title, excerpt, created_at)
                VALUES
                    (?, ?, ?, ?, ?, ?, ?, ?)
            ';

            /** @var Statement $statement */
            $statement = yield $this->dbConnection->prepare($query);

            yield $statement->execute([
                $article->getId(),
                $article->getSourceId(),
                $article->getFeedId(),
                $article->getUrl(),
                $article->getSource(),
                $article->getTitle(),
                $article->getExcerpt(),
                $article->getCreatedAt()->format('Y-m-d H:i:s'),
            ]);
        });
    }

    /**
     * @return Promise<UserArticles>
     */
    public function getArticlesByUser(User $user): Promise
    {
        return call(function () use ($user) {
            $query = '
                SELECT articles.id, articles.source_id, articles.feed_id, articles.url, articles.source, articles.title,
                    articles.excerpt, articles.created_at, read_status.id IS NOT NULL AS read
                FROM articles
                LEFT JOIN read_status ON read_status.article_id = articles.id
                    AND read_status.user_id = ?
                JOIN user_subscriptions ON user_subscriptions.feed_id = articles.feed_id
                JOIN user_categories ON user_categories.id = user_subscriptions.category_id
                WHERE user_categories.user_id = ?
                ORDER BY articles.created_at DESC, articles.source DESC, articles.source_id DESC
                LIMIT 50
                OFFSET 0
            ';

            /** @var Statement $statement */
            $statement = yield $this->dbConnection->prepare($query);

            /** @var ResultSet $result */
            $result = yield $statement->execute([
                $user->getId(),
                $user->getId(),
            ]);

            $articles = [];

            while (yield $result->advance()) {
                $articles[] = new UserArticle(new Article(
                    $result->getCurrent()['id'],
                    $result->getCurrent()['source_id'],
                    $result->getCurrent()['feed_id'],
                    $result->getCurrent()['url'],
                    $result->getCurrent()['source'],
                    $result->getCurrent()['title'],
                    $result->getCurrent()['excerpt'],
                    new \DateTimeImmutable($result->getCurrent()['created_at']),
                ), $result->getCurrent()['read']);
            }

            return new UserArticles(...$articles);
        });
    }

    /**
     * @return Promise<Article|null>
     */
    public function getById(string $id): Promise
    {
        return call(function () use ($id) {
            $query = '
                SELECT id, source_id, feed_id, url, source, title, excerpt, created_at
                FROM articles
                WHERE id = ?
            ';

            /** @var Statement $statement */
            $statement = yield $this->dbConnection->prepare($query);

            /** @var ResultSet $result */
            $result = yield $statement->execute([
                $id,
            ]);

            if (!yield $result->advance()) {
                return null;
            }

            return new Article(
                $result->getCurrent()['id'],
                $result->getCurrent()['source_id'],
                $result->getCurrent()['feed_id'],
                $result->getCurrent()['url'],
                $result->getCurrent()['source'],
                $result->getCurrent()['title'],
                $result->getCurrent()['excerpt'],
                new \DateTimeImmutable($result->getCurrent()['created_at']),
            );
        });
    }

    /**
     * @return Promise<null>
     */
    public function markAsRead(Article $article, User $user): Promise
    {
        return call(function () use ($article, $user) {
            if (yield $this->isRead($article, $user)) {
                return;
            }

            $query = '
                INSERT INTO read_status
                (id, article_id, user_id, created_at)
                VALUES 
                (?, ?, ?, ?)
            ';

            /** @var Statement $statement */
            $statement = yield $this->dbConnection->prepare($query);

            yield $statement->execute([
                generateUuid(),
                $article->getId(),
                $user->getId(),
                (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]);
        });
    }

    /**
     * @return Promise<bool>
     */
    private function isRead(Article $article, User $user): Promise
    {
        return call(function () use ($article, $user) {
            $query = '
                SELECT COUNT(id) AS count
                FROM read_status
                WHERE article_id = ? AND user_id = ?
            ';

            /** @var Statement $statement */
            $statement = yield $this->dbConnection->prepare($query);

            /** @var ResultSet $result */
            $result = yield $statement->execute([
                $article->getId(),
                $user->getId(),
            ]);

            yield $result->advance();

            return (bool) $result->getCurrent()['count'];
        });
    }
}
