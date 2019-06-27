<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Response;

use PeeHaa\FeedMe\Collection\UserArticles as ArticleCollection;
use PeeHaa\FeedMe\Response\UserArticles;
use PHPUnit\Framework\TestCase;

class UserArticlesTest extends TestCase
{
    /** @var UserArticles */
    private $response;

    public function setUp(): void
    {
        $this->response = new UserArticles('requestId', new ArticleCollection());
    }

    public function testToJsonReturnsCorrectKeys(): void
    {
        $responseData = json_decode($this->response->toJson(), true);

        $this->assertArrayHasKey('requestId', $responseData);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('articles', $responseData['data']);
    }

    public function testToJsonReturnsFormattedData(): void
    {
        $responseData = json_decode($this->response->toJson(), true);

        $this->assertSame('requestId', $responseData['requestId']);
        $this->assertCount(0, $responseData['data']['articles']);
    }
}
