<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Entity;

final class Category
{
    /** @var string */
    private $id;

    /** @var string */
    private $name;

    public function __construct(string $id, string $name)
    {
        $this->id   = $id;
        $this->name = $name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string,string>
     */
    public function toArray(): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }
}
