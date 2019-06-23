<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Storage\Subscription;

use Amp\Postgres\Link;
use Amp\Postgres\ResultSet;
use Amp\Promise;
use Amp\Sql\Statement;
use PeeHaa\FeedMe\Entity\Subscription;
use PeeHaa\FeedMe\Entity\Subscriptions;
use PeeHaa\FeedMe\Entity\User;
use function Amp\call;

class PostgresqlRepository implements Repository
{
    /** @var Link */
    private $dbConnection;

    public function __construct(Link $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    /**
     * @return Promise<Subscriptions>
     */
    public function getAllByUser(User $user): Promise
    {
        return call(function () use ($user) {
            $query = '
                SELECT subscriptions.id, subscriptions.feed_id, subscriptions.category_id
                FROM user_subscriptions AS subscriptions
                JOIN user_categories ON user_categories.id = subscriptions.category_id
                WHERE user_categories.user_id = ?
            ';

            /** @var Statement $statement */
            $statement = yield $this->dbConnection->prepare($query);

            /** @var ResultSet $result */
            $result = yield $statement->execute([
                $user->getId(),
            ]);

            $categories = [];

            while (yield $result->advance()) {
                $categories[] = new Subscription(
                    $result->getCurrent()['id'],
                    $result->getCurrent()['feed_id'],
                    $result->getCurrent()['category_id'],
                );
            }

            return new Subscriptions(...$categories);
        });
    }
}
