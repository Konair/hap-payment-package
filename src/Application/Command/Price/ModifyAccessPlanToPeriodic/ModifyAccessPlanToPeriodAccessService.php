<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToPeriodic;

use Carbon\CarbonImmutable;
use Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToPeriodic\Exception\PricePlanNotFoundException;
use Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToPeriodic\Exception\WrongFinishDateException;
use Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToPeriodic\Exception\WrongStartDateException;
use Konair\HAP\Payment\Domain\Model\ItemAccessPlan\PeriodicItemAccessPlan;
use Konair\HAP\Payment\Domain\Model\Price\Exception\PricePlanDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Price\PricePlanRepository;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePlanId;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;

/**
 * @implements ApplicationService<ModifyAccessPlanToPeriodAccessRequest>
 */
final class ModifyAccessPlanToPeriodAccessService implements ApplicationService
{
    public function __construct(
        private PricePlanRepository $repository,
    ) {
    }

    /**
     * @throws WrongRequestTypeException
     * @throws PricePlanNotFoundException
     * @throws WrongStartDateException
     * @throws WrongFinishDateException
     */
    public function execute(Request $request): ModifyAccessPlanToPeriodAccessResponse
    {
        if (!$request instanceof ModifyAccessPlanToPeriodAccessRequest) {
            throw new WrongRequestTypeException();
        }

        $pricePlanId = PricePlanId::create($request->pricePlanId());

        try {
            $pricePlan = $this->repository->byId($pricePlanId);
        } catch (PricePlanDoesNotExistsException) {
            throw new PricePlanNotFoundException();
        }

        $pricePlan->changeItemAccessPlan(new PeriodicItemAccessPlan(
            CarbonImmutable::create(
                $request->startedAtYear(),
                $request->startedAtMonth(),
                $request->startedAtDay(),
                $request->startedAtHour(),
                $request->startedAtMinute(),
            ) ?: throw new WrongStartDateException(),
            CarbonImmutable::create(
                $request->finishedAtYear(),
                $request->finishedAtMonth(),
                $request->finishedAtDay(),
                $request->finishedAtHour(),
                $request->finishedAtMinute(),
            ) ?: throw new WrongFinishDateException(),
        ));

        return ModifyAccessPlanToPeriodAccessResponse::createFromPricePlan($pricePlan);
    }
}
