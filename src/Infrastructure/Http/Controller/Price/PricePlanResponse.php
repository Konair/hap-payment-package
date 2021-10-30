<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Http\Controller\Price;

use Konair\HAP\Payment\Application\Query\Price\PricePlanResponse as PricePlanApplicationResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait PricePlanResponse
{
    protected function createFromServiceResponse(PricePlanApplicationResponse $response): Response
    {
        return new JsonResponse([
            'pricePlanId' => $response->pricePlanId(),
            'accessPlan' => $response->accessPlan(),
            'lengthAccessPlanInterval' => [
                'years' => $response->lengthAccessPlanInterval()?->years,
                'months' => $response->lengthAccessPlanInterval()?->months,
                'weeks' => $response->lengthAccessPlanInterval()?->weeks,
                'days' => $response->lengthAccessPlanInterval()?->dayz,
                'hours' => $response->lengthAccessPlanInterval()?->hours,
                'minutes' => $response->lengthAccessPlanInterval()?->minutes,
                'seconds' => $response->lengthAccessPlanInterval()?->seconds,
            ],
            'periodicItemAccessPlanStartedAt' => $response->periodicItemAccessPlanStartedAt()?->toW3cString(),
            'periodicItemAccessPlanFinishedAt' => $response->periodicItemAccessPlanFinishedAt()?->toW3cString(),
            'priceGrossAmount' => $response->priceGrossAmount(),
            'priceCurrencyIsoCode' => $response->priceCurrencyIsoCode(),
            'availableFrom' => $response->availableFrom()?->toW3cString(),
            'availableTo' => $response->availableTo()?->toW3cString(),
            'links' => [],
        ], Response::HTTP_OK);
    }
}
