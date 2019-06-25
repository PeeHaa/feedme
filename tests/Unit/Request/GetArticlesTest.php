<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Request;

use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Request\GetArticles;
use PHPUnit\Framework\TestCase;

class GetArticlesTest extends TestCase
{
    public function testFromArray(): void
    {
        $request = GetArticles::fromArray([
            'id'   => 'TheId',
            'type' => 'GetArticles',
        ], new Client(1), new User('id', 'username', 'hash'));

        $this->assertInstanceOf(GetArticles::class, $request);
    }
}
