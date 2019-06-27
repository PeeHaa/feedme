<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Controller;

use Amp\Promise;
use PeeHaa\FeedMe\Collection\UserArticles;
use PeeHaa\FeedMe\Request\GetArticles as ArticlesRequest;
use PeeHaa\FeedMe\Request\Request;
use PeeHaa\FeedMe\Response\Response;
use PeeHaa\FeedMe\Response\UserArticles as ArticlesResponse;
use PeeHaa\FeedMe\Storage\Article\Repository as ArticleRepository;
use function Amp\call;

final class GetArticles implements RequestHandler
{
    /** @var ArticleRepository */
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @return Promise<Response>
     */
    public function processRequest(Request $request): Promise
    {
        /** @var ArticlesRequest $request */
        return call(function () use ($request) {
            /** @var UserArticles $articles */
            $articles = yield $this->articleRepository->getArticlesByUser($request->getUser());

            return new ArticlesResponse($request->getId(), $articles);
        });
    }
}
