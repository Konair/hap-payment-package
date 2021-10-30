<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Http\Controller\Price;

use Konair\HAP\Payment\Application\Command\Price\CreatePricePlan\CreatePricePlanRequest;
use Konair\HAP\Payment\Application\Command\Price\CreatePricePlan\CreatePricePlanService;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CreatePricePlanController
{
    use PricePlanResponse;

    public function __invoke(Request $httpRequest): Response
    {
        $itemId = $httpRequest->get('itemId');

        if (!is_string($itemId)) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        $request = new CreatePricePlanRequest($itemId);
        $service = new CreatePricePlanService();

        try {
            $response = $service->execute($request);
        } catch (WrongRequestTypeException $e) {
            return new JsonResponse([
                'referenceName' => $e->referenceName(),
                'referenceId' => $e->referenceId(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->createFromServiceResponse($response);
    }
}
