<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Controller;

use Amp\Success;
use PeeHaa\FeedMe\Controller\GetCategories;
use PeeHaa\FeedMe\Entity\Categories;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Request\GetCategories as Request;
use PeeHaa\FeedMe\Response\UserCategories as Response;
use PeeHaa\FeedMe\Storage\UserCategory\Repository;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class GetCategoriesTest extends TestCase
{
    public function testProcessRequest(): void
    {
        $categoryRepository = $this->createMock(Repository::class);

        $categoryRepository
            ->expects($this->once())
            ->method('getAllByUser')
            ->willReturn(new Success(new Categories()))
        ;

        $controller = new GetCategories($categoryRepository);

        $response = wait($controller->processRequest(
            new Request('id', 'GetCategories', new Client(1), new User('1', 'username', 'hash')),
        ));

        $this->assertInstanceOf(Response::class, $response);
    }
}
