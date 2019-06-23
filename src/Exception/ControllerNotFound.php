<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Exception;

class ControllerNotFound extends Exception
{
    public function __construct(string $controllerClass)
    {
        parent::__construct(
            sprintf('Controller %s could not be found', $controllerClass),
        );
    }
}
