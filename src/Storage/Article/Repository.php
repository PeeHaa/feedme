<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Storage\Article;

use Amp\Promise;
use PeeHaa\FeedMe\Entity\Article;
use PeeHaa\FeedMe\Entity\Articles;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Entity\UserArticles;

interface Repository
{
    /**
     * @return Promise<Articles>
     */
    public function storeNewArticles(Articles $articles): Promise;

    /**
     * @return Promise<UserArticles>
     */
    public function getArticlesByUser(User $user): Promise;

    /**
     * @return Promise<Article|null>
     */
    public function getById(string $id): Promise;

    /**
     * @return Promise<null>
     */
    public function markAsRead(Article $article, User $user): Promise;
}
