<?php declare(strict_types=1);

namespace PeeHaa\FeedMe;

use Amp\Promise;
use Auryn\InjectionException;
use Auryn\Injector;
use HarmonyIO\Validation\Result\Result;
use PeeHaa\FeedMe\Controller\Error as ErrorController;
use PeeHaa\FeedMe\Controller\Factory as ControllerFactory;
use PeeHaa\FeedMe\Entity\User;
use PeeHaa\FeedMe\Exception\InvalidRequest;
use PeeHaa\FeedMe\Http\WebSocket\Client;
use PeeHaa\FeedMe\Request\Error as ErrorRequest;
use PeeHaa\FeedMe\Request\Factory as RequestFactory;
use PeeHaa\FeedMe\Request\Request;
use PeeHaa\FeedMe\Response\Response;
use function Amp\call;

class FrontController
{
    private const VALIDATOR_NAMESPACE = 'PeeHaa\FeedMe\Validator\Request\\';

    /** @var Injector */
    private $auryn;

    /** @var RequestFactory */
    private $requestFactory;

    /** @var ControllerFactory */
    private $controllerFactory;

    public function __construct(Injector $auryn, RequestFactory $requestFactory, ControllerFactory $controllerFactory)
    {
        $this->auryn             = $auryn;
        $this->requestFactory    = $requestFactory;
        $this->controllerFactory = $controllerFactory;
    }

    /**
     * @return Promise<Response>
     */
    public function handleRequest(string $requestDataString, Client $client, ?User $user = null): Promise
    {
        return call(function () use ($requestDataString, $client, $user) {
            try {
                $requestData = json_decode($requestDataString, true, 512, JSON_THROW_ON_ERROR);

                if (!isset($requestData['id'], $requestData['type']) || strpos($requestData['type'], '\\') !== false) {
                    throw new InvalidRequest();
                }

                /** @var Result $validationResult */
                $validationResult = yield $this->validateRequest($requestDataString, $requestData['type']);

                if (!$validationResult->isValid()) {
                    return (new ErrorController())->processRequest(
                        new ErrorRequest($requestData['id'], $validationResult->getFirstError(), $client),
                    );
                }

                $request    = $this->buildRequest($requestData, $client, $user);
                $controller = $this->controllerFactory->buildFromrequest($request);

                return $controller->processRequest($request);
            } catch (\Throwable $e) {
                throw new InvalidRequest();
            }
        });
    }

    /**
     * @return Promise<Result>
     * @throws InjectionException
     */
    private function validateRequest(string $requestData, string $type): Promise
    {
        $validator = sprintf('%s::%s', self::VALIDATOR_NAMESPACE . $type, 'validate');

        return $this->auryn->execute($validator, [
            ':value' => $requestData,
        ]);
    }

    /**
     * @param array<string,mixed> $requestData
     */
    private function buildRequest(array $requestData, Client $client, ?User $user = null): Request
    {
        if ($user === null) {
            return $this->requestFactory->buildFromArray($client, $requestData);
        }

        return $this->requestFactory->buildFromArray($client, $requestData, $user);
    }
}
