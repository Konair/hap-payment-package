<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToLength;

use Carbon\CarbonInterval;
use Exception;
use Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToLength\Exception\PricePlanNotFoundException;
use Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToLength\Exception\WrongDateTimeException;
use Konair\HAP\Payment\Domain\Model\ItemAccessPlan\LengthItemAccessPlan;
use Konair\HAP\Payment\Domain\Model\Price\Exception\PricePlanDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Price\PricePlanRepository;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePlanId;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;

/**
 * @implements ApplicationService<ModifyAccessPlanToLengthAccessRequest>
 */
final class ModifyAccessPlanToLengthAccessService implements ApplicationService
{
    public function __construct(
        private PricePlanRepository $repository,
    ) {
    }

    /**
     * @throws PricePlanNotFoundException
     * @throws WrongDateTimeException
     * @throws WrongRequestTypeException
     */
    public function execute(Request $request): ModifyAccessPlanToLengthAccessResponse
    {
        if (!$request instanceof ModifyAccessPlanToLengthAccessRequest) {
            throw new WrongRequestTypeException();
        }

        $pricePlanId = PricePlanId::create($request->pricePlanId());

        try {
            $pricePlan = $this->repository->byId($pricePlanId);
        } catch (PricePlanDoesNotExistsException) {
            throw new PricePlanNotFoundException();
        }

        try {
            $pricePlan->changeItemAccessPlan(new LengthItemAccessPlan(
                CarbonInterval::create(
                    $request->years(),
                    $request->months(),
                    $request->weeks(),
                    $request->days(),
                    $request->hours(),
                    $request->minutes(),
                )
            ));
        } catch (Exception) {
            throw new WrongDateTimeException();
        }

        return ModifyAccessPlanToLengthAccessResponse::createFromPricePlan($pricePlan);
    }
}
