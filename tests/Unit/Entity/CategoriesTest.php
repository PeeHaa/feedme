<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Entity;

use PeeHaa\FeedMe\Entity\Categories;
use PeeHaa\FeedMe\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoriesTest extends TestCase
{
    public function testToArrayWhenEmptyCollection(): void
    {
        $this->assertSame([], (new Categories())->toArray());
    }

    public function testToArrayWhenFilled(): void
    {
        $this->assertSame([
            [
                'id'   => 'id1',
                'name' => 'name1',
            ],
            [
                'id'   => 'id2',
                'name' => 'name2',
            ],
        ], (new Categories(new Category('id1', 'name1'), new Category('id2', 'name2')))->toArray());
    }
}
