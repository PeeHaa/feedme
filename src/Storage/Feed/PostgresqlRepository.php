<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Storage\Feed;

use Amp\Postgres\Link;
use Amp\Postgres\ResultSet;
use Amp\Promise;
use PeeHaa\FeedMe\Collection\Feeds;
use PeeHaa\FeedMe\Entity\Feed;
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
     * @return Promise<Feeds>
     */
    public function getAll(): Promise
    {
        return call(function () {
            $query = 'SELECT id, crawler, interval FROM feeds';

            /** @var ResultSet $result */
            $result = yield $this->dbConnection->query($query);

            $feeds = [];

            while (yield $result->advance()) {
                $feeds[] = new Feed(
                    $result->getCurrent()['id'],
                    $result->getCurrent()['crawler'],
                    new \DateInterval($result->getCurrent()['interval']),
                );
            }

            return new Feeds(...$feeds);
        });
    }
}
