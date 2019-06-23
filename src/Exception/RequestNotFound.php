<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Exception;

class RequestNotFound extends Exception
{
    public function __construct(string $requestClass)
    {
        parent::__construct(
            sprintf('Request %s could not be found', $requestClass),
        );
    }
}
