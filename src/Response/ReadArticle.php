<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Response;

use PeeHaa\FeedMe\Entity\Article;

final class ReadArticle implements Response
{
    /** @var Article */
    private $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function toJson(): string
    {
        return json_encode([
            'requestId'  => 'ReadArticle',
            'data' => [
                'article' => $this->article->toArray(),
            ],
        ], JSON_THROW_ON_ERROR);
    }
}
