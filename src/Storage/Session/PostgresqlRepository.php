<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Storage\Session;

use Amp\Postgres\Link;
use Amp\Postgres\ResultSet;
use Amp\Promise;
use Amp\Sql\Statement;
use PeeHaa\FeedMe\Entity\Session;
use function Amp\call;

final class PostgresqlRepository implements Repository
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
    public function store(Session $session): Promise
    {
        return call(function () use ($session) {
            $query = '
                INSERT INTO sessions
                (id, client_id, user_id, token, expiration)
                VALUES
                (?, ?, ?, ?, ?)
            ';

            /** @var Statement $statement */
            $statement = yield $this->dbConnection->prepare($query);

            yield $statement->execute([
                $session->getId(),
                $session->getClientId(),
                $session->getUserId(),
                $session->getToken(),
                $session->getExpiration()->format('Y-m-d H:i:s'),
            ]);
        });
    }

    /**
     * @return Promise<Session|null>
     */
    public function get(string $id, string $userId): Promise
    {
        return call(function () use ($id, $userId) {
            $query = '
                SELECT id, client_id, user_id, token, expiration
                FROM sessions
                WHERE id = ? AND user_id = ? AND expiration > ?
            ';

            /** @var Statement $statement */
            $statement = yield $this->dbConnection->prepare($query);

            /** @var ResultSet $result */
            $result = yield $statement->execute([
                $id,
                $userId,
                (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ]);

            if (!yield $result->advance()) {
                return null;
            }

            return new Session(
                $result->getCurrent()['id'],
                $result->getCurrent()['client_id'],
                $result->getCurrent()['user_id'],
                $result->getCurrent()['token'],
                new \DateTimeImmutable($result->getCurrent()['expiration']),
            );
        });
    }

    /**
     * @return Promise<null>
     */
    public function delete(Session $session): Promise
    {
        return call(function () use ($session) {
            $query = '
                DELETE FROM sessions
                WHERE id = ?
            ';

            /** @var Statement $statement */
            $statement = yield $this->dbConnection->prepare($query);

            yield $statement->execute([
                $session->getId(),
            ]);
        });
    }
}
