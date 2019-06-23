<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Controller;

use Auryn\Injector;
use PeeHaa\FeedMe\Exception\ControllerNotFound;
use PeeHaa\FeedMe\Request\Request;

final class Factory
{
    /** @var Injector */
    private $auryn;

    public function __construct(Injector $auryn)
    {
        $this->auryn = $auryn;
    }

    public function buildFromRequest(Request $request): RequestHandler
    {
        $controllerClass = 'PeeHaa\FeedMe\Controller\\' . $request->getType();

        if (!class_exists($controllerClass)) {
            throw new ControllerNotFound($controllerClass);
        }

        return $this->auryn->make($controllerClass);
    }
}
