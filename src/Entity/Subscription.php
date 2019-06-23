<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Entity;

class Subscription
{
    /** @var string */
    private $id;

    /** @var string */
    private $feedId;

    /** @var string */
    private $categoryId;

    public function __construct(string $id, string $feedId, string $categoryId)
    {
        $this->id         = $id;
        $this->feedId     = $feedId;
        $this->categoryId = $categoryId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFeedId(): string
    {
        return $this->feedId;
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }
}
