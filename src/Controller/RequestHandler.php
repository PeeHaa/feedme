<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Controller;

use Amp\Promise;
use PeeHaa\FeedMe\Request\Request;
use PeeHaa\FeedMe\Response\Response;

interface RequestHandler
{
    /**
     * @return Promise<Response>
     */
    public function processRequest(Request $request): Promise;
}
