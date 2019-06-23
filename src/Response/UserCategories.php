<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Response;

use PeeHaa\FeedMe\Entity\Categories;

final class UserCategories implements Response
{
    /** @var string */
    private $requestId;

    /** @var Categories */
    private $categories;

    public function __construct(string $requestId, Categories $categories)
    {
        $this->requestId  = $requestId;
        $this->categories = $categories;
    }

    public function toJson(): string
    {
        return json_encode([
            'requestId' => $this->requestId,
            'status'    => 200,
            'data'      => [
                'categories' => $this->categories->toArray(),
            ],
        ], JSON_THROW_ON_ERROR);
    }
}
