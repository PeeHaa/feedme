<?php declare(strict_types=1);

namespace PeeHaa\FeedMeTest\Fakes;

use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Request\Request;

class ControllerFoundRequest extends Request
{
    public function __construct()
    {
        parent::__construct('TheId', 'Index', new Client(1));
    }

    /**
     * phpcs:disable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
     *
     * @param array<mixed> $json
     */
    public static function fromArray(array $json): Request
    {
        // phpcs:enable SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
        // TODO: Implement fromArray() method.
    }
}
