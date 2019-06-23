<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Storage\User;

use Amp\Promise;
use PeeHaa\FeedMe\Entity\NewUser;
use PeeHaa\FeedMe\Entity\User;

interface Repository
{
    /**
     * @return Promise<null>
     */
    public function create(NewUser $user): Promise;

    /**
     * @return Promise<User|null>
     */
    public function getById(string $id): Promise;

    /**
     * @return Promise<User|null>
     */
    public function getByEmailAddress(string $emailAddress): Promise;
}
