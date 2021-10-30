<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Http\Controller\Billing;

use Konair\HAP\Payment\Application\Command\Billing\CreateBillingData\CreateBillingDataRequest;
use Konair\HAP\Payment\Application\Command\Billing\CreateBillingData\CreateBillingDataService;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateBillingDataController
{
    use BillingDataResponse;

    public function __invoke(): Response
    {
        $request = new CreateBillingDataRequest();
        $service = new CreateBillingDataService();

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
