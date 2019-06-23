<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Queue;

use Amp\Promise;
use Auryn\Injector;
use PeeHaa\FeedMe\Entity\Articles;
use PeeHaa\FeedMe\Entity\Feed;
use PeeHaa\FeedMe\Event\NewArticleManager;
use PeeHaa\FeedMe\Storage\Article\Repository as ArticleRepository;
use function Amp\call;

final class Worker
{
    /** @var Injector */
    private $auryn;

    /** @var ArticleRepository */
    private $articleRepository;

    /** @var NewArticleManager */
    private $newArticleManager;

    public function __construct(
        Injector $auryn,
        ArticleRepository $articleRepository,
        NewArticleManager $newArticleManager
    ) {
        $this->auryn             = $auryn;
        $this->articleRepository = $articleRepository;
        $this->newArticleManager = $newArticleManager;
    }

    /**
     * @return Promise<int>
     */
    public function run(Feed $feed): Promise
    {
        return call(function () use ($feed) {
            $articles = yield $this->auryn->execute([$feed->getCrawler(), 'retrieve']);

            $newArticles = yield $this->articleRepository->storeNewArticles($articles);

            $this->publishNewArticles($newArticles);

            return count($newArticles);
        });
    }

    private function publishNewArticles(Articles $articles): void
    {
        foreach ($articles as $article) {
            $this->newArticleManager->publish($article);
        }
    }
}
