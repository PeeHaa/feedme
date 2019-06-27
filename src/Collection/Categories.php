<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Collection;

use PeeHaa\FeedMe\Entity\Category;

class Categories
{
    /** @var array<Category> */
    private $categories = [];

    public function __construct(Category ...$categories)
    {
        $this->categories = $categories;
    }

    /**
     * @return array<array<string,string>>
     */
    public function toArray(): array
    {
        $categories = [];

        foreach ($this->categories as $category) {
            $categories[] = $category->toArray();
        }

        return $categories;
    }
}
