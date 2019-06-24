<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Entity;

use PeeHaa\FeedMe\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    /** @var Category */
    private $category;

    public function setUp(): void
    {
        $this->category = new Category('id', 'name');
    }

    public function testGetId(): void
    {
        $this->assertSame('id', $this->category->getId());
    }

    public function testGetName(): void
    {
        $this->assertSame('name', $this->category->getName());
    }

    public function testToArray(): void
    {
        $this->assertSame([
            'id'   => 'id',
            'name' => 'name',
        ], $this->category->toArray());
    }
}
