<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Controller;

use Amp\Promise;
use PeeHaa\FeedMe\Entity\Categories;
use PeeHaa\FeedMe\Request\GetCategories as GetCategoriesRequest;
use PeeHaa\FeedMe\Request\Request;
use PeeHaa\FeedMe\Response\Response;
use PeeHaa\FeedMe\Response\UserCategories;
use PeeHaa\FeedMe\Storage\UserCategory\Repository as CategoryRepository;
use function Amp\call;

final class GetCategories implements RequestHandler
{
    /** @var CategoryRepository */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return Promise<Response>
     */
    public function processRequest(Request $request): Promise
    {
        /** @var GetCategoriesRequest $request */
        return call(function () use ($request) {
            /** @var Categories $categories */
            $categories = yield $this->categoryRepository->getAllByUser($request->getUser());

            return new UserCategories($request->getId(), $categories);
        });
    }
}
