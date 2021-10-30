<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Http\Controller\Billing;

use Konair\HAP\Payment\Application\Query\Billing\BillingDataResponse as BillingDataApplicationResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait BillingDataResponse
{
    protected function createFromServiceResponse(BillingDataApplicationResponse $response): Response
    {
        return new JsonResponse([
            'billingDataId' => $response->billingDataId(),
            'userId' => $response->userId(),
            'name' => [
                'prefix' => $response->namePrefix(),
                'firstName' => $response->nameFirstName(),
                'lastName' => $response->nameLastName(),
            ],
            'firmName' => $response->firmName(),
            'address' => [
                'country' => $response->addressCountry(),
                'zip' => $response->addressZip(),
                'city' => $response->addressCity(),
                'line' => $response->addressLine(),
            ],
            'phoneNumber' => $response->phoneNumber(),
            'links' => [],
        ], Response::HTTP_OK);
    }
}
