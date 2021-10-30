<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToLifetime;

use Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToLifetime\Exception\PricePlanNotFoundException;
use Konair\HAP\Payment\Domain\Model\ItemAccessPlan\LifetimeItemAccessPlan;
use Konair\HAP\Payment\Domain\Model\Price\Exception\PricePlanDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Price\PricePlanRepository;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePlanId;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;

/**
 * @implements ApplicationService<ModifyAccessPlanToLifetimeAccessRequest>
 */
final class ModifyAccessPlanToLifetimeAccessService implements ApplicationService
{
    public function __construct(
        private PricePlanRepository $repository,
    ) {
    }

    /**
     * @throws WrongRequestTypeException
     * @throws PricePlanNotFoundException
     */
    public function execute(Request $request): ModifyAccessPlanToLifetimeAccessResponse
    {
        if (!$request instanceof ModifyAccessPlanToLifetimeAccessRequest) {
            throw new WrongRequestTypeException();
        }

        $pricePlanId = PricePlanId::create($request->pricePlanId());

        try {
            $pricePlan = $this->repository->byId($pricePlanId);
        } catch (PricePlanDoesNotExistsException) {
            throw new PricePlanNotFoundException();
        }

        $pricePlan->changeItemAccessPlan(new LifetimeItemAccessPlan());

        return ModifyAccessPlanToLifetimeAccessResponse::createFromPricePlan($pricePlan);
    }
}
