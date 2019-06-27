<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Response;

use PeeHaa\FeedMe\Collection\Categories;
use PeeHaa\FeedMe\Response\UserCategories;
use PHPUnit\Framework\TestCase;

class UserCategoriesTest extends TestCase
{
    /** @var UserCategories */
    private $response;

    public function setUp(): void
    {
        $this->response = new UserCategories('requestId', new Categories());
    }

    public function testToJsonReturnsCorrectKeys(): void
    {
        $responseData = json_decode($this->response->toJson(), true);

        $this->assertArrayHasKey('requestId', $responseData);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('categories', $responseData['data']);
    }

    public function testToJsonReturnsFormattedData(): void
    {
        $responseData = json_decode($this->response->toJson(), true);

        $this->assertSame('requestId', $responseData['requestId']);
        $this->assertCount(0, $responseData['data']['categories']);
    }
}
