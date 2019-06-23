<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Entity;

class UserArticle
{
    /** @var Article */
    private $article;

    /** @var bool */
    private $read;

    public function __construct(Article $article, bool $read)
    {
        $this->article = $article;
        $this->read    = $read;
    }

    public function getArticle(): Article
    {
        return $this->article;
    }

    public function isRead(): bool
    {
        return $this->read;
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $article = $this->article->toArray();

        $article['read'] = $this->read;

        return $article;
    }
}
