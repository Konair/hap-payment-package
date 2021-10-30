<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Http\Controller\Price;

use Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToPeriodic\Exception\PricePlanNotFoundException;
use Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToPeriodic\Exception\WrongFinishDateException;
use Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToPeriodic\Exception\WrongStartDateException;
use Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToPeriodic\ModifyAccessPlanToPeriodAccessRequest;
use Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToPeriodic\ModifyAccessPlanToPeriodAccessService;
use Konair\HAP\Payment\Domain\Model\Price\PricePlanRepository;
use Konair\HAP\Payment\Infrastructure\Http\Controller\InvalidRequestParameterException;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ModifyAccessPlanToPeriodAccessController
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

        $service = new ModifyAccessPlanToPeriodAccessService($this->planRepository);

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
        } catch (WrongStartDateException | WrongFinishDateException $e) {
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
        Request $httpRequest,
    ): ModifyAccessPlanToPeriodAccessRequest {
        if (is_null($pricePlanId)) {
            throw InvalidRequestParameterException::create(new JsonResponse([], Response::HTTP_NOT_FOUND));
        }

        return new ModifyAccessPlanToPeriodAccessRequest(
            $pricePlanId,
            $this->intValue('startedAt.year', 'missingOrWrongStartDate', $httpRequest),
            $this->intValue('startedAt.month', 'missingOrWrongStartDate', $httpRequest),
            $this->intValue('startedAt.day', 'missingOrWrongStartDate', $httpRequest),
            $this->intValue('startedAt.hour', 'missingOrWrongStartTime', $httpRequest),
            $this->intValue('startedAt.minute', 'missingOrWrongStartTime', $httpRequest),
            $this->intValue('finishedAt.year', 'missingOrWrongFinishDate', $httpRequest),
            $this->intValue('finishedAt.month', 'missingOrWrongFinishDate', $httpRequest),
            $this->intValue('finishedAt.day', 'missingOrWrongFinishDate', $httpRequest),
            $this->intValue('finishedAt.hour', 'missingOrWrongFinishTime', $httpRequest),
            $this->intValue('finishedAt.minute', 'missingOrWrongFinishTime', $httpRequest),
        );
    }


    /** @throws InvalidRequestParameterException */
    private function intValue(string $key, string $ref, Request $httpRequest): int
    {
        [$requestKey, $arrayKey] = explode('.', $key);
        $dateTime = $httpRequest->get($requestKey, []);

        $value = is_array($dateTime)
            ? $dateTime[$arrayKey] ?? null
            : null;

        if (!is_int($value)) {
            throw InvalidRequestParameterException::create(
                new JsonResponse(['ref' => $ref], Response::HTTP_BAD_REQUEST)
            );
        }

        return $value;
    }
}
