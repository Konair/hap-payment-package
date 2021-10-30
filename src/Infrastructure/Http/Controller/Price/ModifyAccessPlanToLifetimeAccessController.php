<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Http\Controller\Price;

use Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToLifetime\Exception\PricePlanNotFoundException;
use Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToLifetime\ModifyAccessPlanToLifetimeAccessRequest;
use Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToLifetime\ModifyAccessPlanToLifetimeAccessService;
use Konair\HAP\Payment\Domain\Model\Price\PricePlanRepository;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @SuppressWarnings(PHPMD.LongClassName)
 */
final class ModifyAccessPlanToLifetimeAccessController
{
    use PricePlanResponse;

    public function __construct(private PricePlanRepository $planRepository)
    {
    }

    public function __invoke(string|null $pricePlanId): Response
    {
        if (is_null($pricePlanId)) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        $request = new ModifyAccessPlanToLifetimeAccessRequest($pricePlanId);
        $service = new ModifyAccessPlanToLifetimeAccessService($this->planRepository);

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
        }

        return $this->createFromServiceResponse($response);
    }
}
