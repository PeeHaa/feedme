<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Event;

use Amp\Success;
use PeeHaa\FeedMe\Entity\Article;
use PeeHaa\FeedMe\Event\NewArticleManager;
use PeeHaa\FeedMeTest\Fakes\CallableClass;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class NewArticleManagerTest extends TestCase
{
    public function testListenReturnsListenerId(): void
    {
        $id = (new NewArticleManager())->listen(function () {});

        $this->assertRegExp('~^[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}$~', $id);
    }

    public function testPublishExecutesListeners(): void
    {
        $spy1 = $this->createMock(CallableClass::class);
        $spy2 = $this->createMock(CallableClass::class);

        $spy1->expects($this->once())
            ->method('__invoke')
            ->willReturn(new Success())
        ;

        $spy2->expects($this->once())
            ->method('__invoke')
            ->willReturn(new Success())
        ;

        $manager = new NewArticleManager();

        $manager->listen($spy1);
        $manager->listen($spy2);

        wait($manager->publish(
            new Article('id', 'sourceId', 'feedId', 'url', 'source', 'title', 'excerpt', new \DateTimeImmutable()),
        ));
    }
}
