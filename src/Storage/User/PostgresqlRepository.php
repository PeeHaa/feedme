<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Storage\User;

use Amp\Postgres\Link;
use Amp\Postgres\ResultSet;
use Amp\Promise;
use Amp\Sql\Statement;
use PeeHaa\FeedMe\Entity\NewUser;
use PeeHaa\FeedMe\Entity\User;
use function Amp\call;
use function Amp\ParallelFunctions\parallel;

class PostgresqlRepository implements Repository
{
    /** @var Link */
    private $dbConnection;

    public function __construct(Link $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    /**
     * @return Promise<null>
     */
    public function create(NewUser $user): Promise
    {
        return call(function () use ($user) {
            $query = '
                INSERT INTO users
                    (id, email_address, password)
                VALUES
                    (?, ?, ?)
            ';

            /** @var Statement $statement */
            $statement = yield $this->dbConnection->prepare($query);

            yield $statement->execute([
                $user->getId(),
                hash('sha512', $user->getUsername()),
                yield parallel(static function () use ($user) {
                    return password_hash($user->getPassword(), PASSWORD_DEFAULT, ['cost' => 12]);
                })(),
            ]);
        });
    }

    /**
     * @return Promise<User|null>
     */
    public function getById(string $id): Promise
    {
        return call(function () use ($id) {
            $query = '
                SELECT id, email_address, password
                FROM users
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

            return new User(
                $result->getCurrent()['id'],
                $result->getCurrent()['email_address'],
                $result->getCurrent()['password'],
            );
        });
    }

    /**
     * @return Promise<User|null>
     */
    public function getByEmailAddress(string $emailAddress): Promise
    {
        return call(function () use ($emailAddress) {
            $query = '
                SELECT id, email_address, password
                FROM users
                WHERE email_address = ?
            ';

            /** @var Statement $statement */
            $statement = yield $this->dbConnection->prepare($query);

            /** @var ResultSet $result */
            $result = yield $statement->execute([
                hash('sha512', $emailAddress),
            ]);

            if (!yield $result->advance()) {
                return null;
            }

            return new User(
                $result->getCurrent()['id'],
                $result->getCurrent()['email_address'],
                $result->getCurrent()['password'],
            );
        });
    }
}
