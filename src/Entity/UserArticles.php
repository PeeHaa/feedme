<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Entity;

final class UserArticles implements \Iterator, \Countable
{
    /** @var array<UserArticle> */
    private $articles = [];

    public function __construct(UserArticle ...$articles)
    {
        $this->articles = $articles;
    }

    public function add(UserArticle $article): void
    {
        $this->articles[] = $article;
    }

    public function current(): UserArticle
    {
        return current($this->articles);
    }

    public function next(): void
    {
        next($this->articles);
    }

    /**
     * @return int|string|null
     */
    public function key()
    {
        return key($this->articles);
    }

    public function valid(): bool
    {
        return $this->key() !== null;
    }

    public function rewind(): void
    {
        reset($this->articles);
    }

    public function count(): int
    {
        return count($this->articles);
    }

    /**
     * @return array<int,array<string,string>>
     */
    public function toArray(): array
    {
        $articles = [];

        foreach ($this->articles as $article) {
            $articles[] = $article->toArray();
        }

        return $articles;
    }
}
