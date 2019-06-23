<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Storage\Session;

use Amp\Promise;
use PeeHaa\FeedMe\Entity\Session;

interface Repository
{
    /**
     * @return Promise<null>
     */
    public function store(Session $session): Promise;

    /**
     * @return Promise<Session|null>
     */
    public function get(string $id, string $userId): Promise;

    /**
     * @return Promise<null>
     */
    public function delete(Session $session): Promise;
}
