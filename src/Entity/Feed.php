<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Entity;

final class Feed
{
    /** @var string */
    private $id;

    /** @var string */
    private $crawler;

    /** @var \DateInterval */
    private $interval;

    public function __construct(string $id, string $crawler, \DateInterval $interval)
    {
        $this->id       = $id;
        $this->crawler  = $crawler;
        $this->interval = $interval;
    }

    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        return new self($data['id'], $data['crawler'], unserialize($data['interval']));
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCrawler(): string
    {
        return $this->crawler;
    }

    public function getInterval(): \DateInterval
    {
        return $this->interval;
    }

    public function toJson(): string
    {
        return json_encode([
            'id'       => $this->id,
            'crawler'  => $this->crawler,
            'interval' => serialize($this->interval),
        ], JSON_THROW_ON_ERROR);
    }
}
