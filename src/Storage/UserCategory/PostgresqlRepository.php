<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Storage\UserCategory;

use Amp\Postgres\Link;
use Amp\Postgres\ResultSet;
use Amp\Promise;
use Amp\Sql\Statement;
use PeeHaa\FeedMe\Collection\Categories;
use PeeHaa\FeedMe\Entity\Category;
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
     * @return Promise<Categories>
     */
    public function getAllByUser(User $user): Promise
    {
        return call(function () use ($user) {
            $query = '
                SELECT categories.id AS category_id, categories.name AS category_name
                FROM user_categories AS categories
                WHERE categories.user_id = ?
            ';

            /** @var Statement $statement */
            $statement = yield $this->dbConnection->prepare($query);

            /** @var ResultSet $result */
            $result = yield $statement->execute([
                $user->getId(),
            ]);

            $categories = [];

            while (yield $result->advance()) {
                $categories[] = new Category(
                    $result->getCurrent()['category_id'],
                    $result->getCurrent()['category_name'],
                );
            }

            return new Categories(...$categories);
        });
    }
}
