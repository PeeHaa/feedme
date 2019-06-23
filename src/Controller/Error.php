<?php declare(strict_types=1);

namespace PeeHaa\FeedMe\Controller;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Result\Error as ErrorResult;
use PeeHaa\FeedMe\Request\Error as ErrorRequest;
use PeeHaa\FeedMe\Request\Request;
use PeeHaa\FeedMe\Response\Error as ErrorResponse;
use PeeHaa\FeedMe\Response\Response;

final class Error implements RequestHandler
{
    /**
     * @return Promise<Response>
     */
    public function processRequest(Request $request): Promise
    {
        $fieldErrors = [];

        /** @var ErrorRequest $request */
        /** @var array<ErrorResult> $errors */
        foreach ($request->getErrors() as $fieldName => $errors) {
            $fieldErrors[$fieldName] = $errors[0]->getMessage();
        }

        return new Success(new ErrorResponse($request->getId(), $fieldErrors));
    }
}
