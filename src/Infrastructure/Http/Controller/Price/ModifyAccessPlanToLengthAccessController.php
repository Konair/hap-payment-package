<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Http\Controller\Price;

use Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToLength\Exception\PricePlanNotFoundException;
use Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToLength\Exception\WrongDateTimeException;
use Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToLength\ModifyAccessPlanToLengthAccessRequest;
use Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToLength\ModifyAccessPlanToLengthAccessService;
use Konair\HAP\Payment\Domain\Model\Price\PricePlanRepository;
use Konair\HAP\Payment\Infrastructure\Http\Controller\InvalidRequestParameterException;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ModifyAccessPlanToLengthAccessController
{
    use PricePlanResponse;

    public function __construct(private PricePlanRepository $planRepository)
    {
    }

    public function __invoke(string|null $pricePlanId, Request $httpRequest): Response
    {
        try {
            $request = $this->createRequest($pricePlanId, $httpRequest);
        } catch (InvalidRequestParameterException $e) {
            return $e->response();
        }

        $service = new ModifyAccessPlanToLengthAccessService($this->planRepository);

        try {
            $response = $service->execute($request);
        } catch (WrongRequestTypeException $e) {
            return new JsonResponse([
                'referenceName' => $e->referenceName(),
                'referenceId' => $e->referenceId(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (PricePlanNotFoundException $e) {
            return new JsonResponse([
                'referenceName' => $e->referenceName(),
                'referenceId' => $e->referenceId(),
            ], Response::HTTP_NOT_FOUND);
        } catch (WrongDateTimeException $e) {
            return new JsonResponse([
                'referenceName' => $e->referenceName(),
                'referenceId' => $e->referenceId(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->createFromServiceResponse($response);
    }

    /** @throws InvalidRequestParameterException */
    private function createRequest(
        string|null $pricePlanId,
        Request $httpRequest
    ): ModifyAccessPlanToLengthAccessRequest {
        if (is_null($pricePlanId)) {
            throw InvalidRequestParameterException::create(new JsonResponse([], Response::HTTP_NOT_FOUND));
        }

        return new ModifyAccessPlanToLengthAccessRequest(
            $pricePlanId,
            $this->intValue('years', $httpRequest),
            $this->intValue('months', $httpRequest),
            $this->intValue('weeks', $httpRequest),
            $this->intValue('days', $httpRequest),
            $this->intValue('hours', $httpRequest),
            $this->intValue('minutes', $httpRequest),
            $this->intValue('seconds', $httpRequest),
        );
    }

    /** @throws InvalidRequestParameterException */
    private function intValue(string $key, Request $httpRequest): int
    {
        $value = $httpRequest->get($key, 0);

        if (!is_int($value)) {
            throw InvalidRequestParameterException::create(
                new JsonResponse([], Response::HTTP_BAD_REQUEST)
            );
        }

        return $value;
    }
}
