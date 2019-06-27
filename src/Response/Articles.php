<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Response;

use PeeHaa\FeedMe\Collection\Articles as ArticleCollection;

final class Articles implements Response
{
    /** @var string */
    private $requestId;

    /** @var ArticleCollection */
    private $articles;

    public function __construct(string $requestId, ArticleCollection $articles)
    {
        $this->requestId = $requestId;
        $this->articles  = $articles;
    }

    public function toJson(): string
    {
        return json_encode([
            'requestId' => $this->requestId,
            'status'    => 200,
            'data'      => [
                'articles' => $this->articles->toArray(),
            ],
        ], JSON_THROW_ON_ERROR);
    }
}
