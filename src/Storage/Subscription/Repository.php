<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Storage\Subscription;

use Amp\Promise;
use PeeHaa\FeedMe\Collection\Categories;
use PeeHaa\FeedMe\Entity\User;

interface Repository
{
    /**
     * @return Promise<Categories>
     */
    public function getAllByUser(User $user): Promise;
}
