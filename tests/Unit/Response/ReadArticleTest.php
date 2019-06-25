<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Response;

use PeeHaa\FeedMe\Entity\Article;
use PeeHaa\FeedMe\Response\ReadArticle;
use PHPUnit\Framework\TestCase;

class ReadArticleTest extends TestCase
{
    /** @var ReadArticle */
    private $response;

    public function setUp(): void
    {
        $this->response = new ReadArticle(
            new Article('id', 'sourceId', 'feedId', 'url', 'source', 'title', 'excerpt', new \DateTimeImmutable()),
        );
    }

    public function testToJsonReturnsCorrectKeys(): void
    {
        $responseData = json_decode($this->response->toJson(), true);

        $this->assertArrayHasKey('requestId', $responseData);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('article', $responseData['data']);
    }

    public function testToJsonReturnsFormattedData(): void
    {
        $responseData = json_decode($this->response->toJson(), true);

        $this->assertSame('ReadArticle', $responseData['requestId']);
        $this->assertCount(8, $responseData['data']['article']);
    }
}
