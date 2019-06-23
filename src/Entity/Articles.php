<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Entity;

final class Articles implements \Iterator, \Countable
{
    /** @var array<Article> */
    private $articles = [];

    public function __construct(Article ...$articles)
    {
        $this->articles = $articles;
    }

    public function add(Article $article): void
    {
        $this->articles[] = $article;
    }

    public function current(): Article
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
