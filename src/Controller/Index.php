<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Controller;

use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Status;
use Amp\Promise;
use function Amp\call;
use function Amp\File\get;

final class Index implements RequestHandler
{
    /**
     * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     *
     * @return Promise<Response>
     */
    public function handleRequest(Request $request): Promise
    {
        // phpcs:enable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
        return call(static function () {
            return new Response(
                Status::OK,
                ['content-type' => 'text/html; charset=utf-8'],
                yield get(__DIR__ . '/../../public/index.html'),
            );
        });
    }
}
