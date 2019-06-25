<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Unit\Response;

use PeeHaa\FeedMe\Entity\Article;
use PeeHaa\FeedMe\Entity\Articles as ArticleCollection;
use PeeHaa\FeedMe\Response\Articles;
use PHPUnit\Framework\TestCase;

class ArticlesTest extends TestCase
{
    public function testToJson(): void
    {
        $response = new Articles('requestId', new ArticleCollection(
            new Article('id', 'sourceId', 'feedId', 'url', 'source', 'title', 'excerpt', new \DateTimeImmutable('@0')),
        ));

        $expectedJson = json_encode([
            'requestId' => 'requestId',
            'status'    => 200,
            'data'      => [
                'articles' => [
                    [
                        'id'        => 'id',
                        'sourceId'  => 'sourceId',
                        'feedId'    => 'feedId',
                        'url'       => 'url',
                        'source'    => 'source',
                        'title'     => 'title',
                        'excerpt'   => 'excerpt',
                        'createdAt' => '1970-01-01 00:00:00',
                    ],
                ],
            ],
        ]);

        $this->assertSame($expectedJson, $response->toJson());
    }
}
