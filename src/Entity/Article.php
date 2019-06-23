<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Entity;

final class Article
{
    /** @var string */
    private $id;

    /** @var string */
    private $sourceId;

    /** @var string */
    private $feedId;

    /** @var string */
    private $url;

    /** @var string */
    private $source;

    /** @var string */
    private $title;

    /** @var string */
    private $excerpt;

    /** @var \DateTimeImmutable */
    private $createdAt;

    public function __construct(
        string $id,
        string $sourceId,
        string $feedId,
        string $url,
        string $source,
        string $title,
        string $excerpt,
        \DateTimeImmutable $createdAt
    ) {
        $this->id        = $id;
        $this->sourceId  = $sourceId;
        $this->feedId    = $feedId;
        $this->url       = $url;
        $this->source    = $source;
        $this->title     = $title;
        $this->excerpt   = $excerpt;
        $this->createdAt = $createdAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSourceId(): string
    {
        return $this->sourceId;
    }

    public function getFeedId(): string
    {
        return $this->feedId;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return array<string,string>
     */
    public function toArray(): array
    {
        return [
            'id'        => $this->id,
            'sourceId'  => $this->sourceId,
            'feedId'    => $this->feedId,
            'url'       => $this->url,
            'source'    => $this->source,
            'title'     => $this->title,
            'excerpt'   => $this->excerpt,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
