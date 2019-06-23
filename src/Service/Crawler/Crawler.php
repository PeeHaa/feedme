<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Service\Crawler;

use Amp\Promise;
use PeeHaa\FeedMe\Entity\Articles;

interface Crawler
{
    /**
     * @return Promise<Articles>
     */
    public function retrieve(): Promise;
}
