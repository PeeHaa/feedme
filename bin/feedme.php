<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Bin;

use Amp\Loop;
use Auryn\Injector;
use Monolog\Logger;
use PeeHaa\FeedMe\Http\Server;
use PeeHaa\FeedMe\Queue\Manager;
use Psr\Log\NullLogger;
use function Amp\asyncCall;

require_once __DIR__ . '/../bootstrap.php';

$logger = new NullLogger();

if (in_array('--debug', $argv, true)) {
    /** @var Injector $auryn */
    /** @var Logger $logger */
    $logger = $auryn->make(Logger::class);
}

$logger->info('Starting FeedMe application');

Loop::run(static function () use ($auryn, $logger): void {
    asyncCall(static function () use ($auryn, $logger) {
        $logger->info('Starting FeedMe queue');

        yield $auryn->execute([Manager::class, 'start']);
    });

    asyncCall(static function () use ($auryn, $logger) {
        $logger->info('Starting FeedMe web server');

        yield $auryn->execute([Server::class, 'start']);
    });
});
