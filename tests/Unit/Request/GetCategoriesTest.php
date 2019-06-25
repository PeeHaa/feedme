<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Request;

use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Request\GetCategories;
use PHPUnit\Framework\TestCase;

class GetCategoriesTest extends TestCase
{
    public function testFromArray(): void
    {
        $request = GetCategories::fromArray([
            'id'   => 'TheId',
            'type' => 'GetCategories',
        ], new Client(1), new User('id', 'username', 'hash'));

        $this->assertInstanceOf(GetCategories::class, $request);
    }
}
