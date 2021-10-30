<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\ModifyAvailableTo;

use Carbon\CarbonImmutable;
use Konair\HAP\Payment\Application\Command\Price\ModifyAvailableTo\Exception\PricePlanNotFoundException;
use Konair\HAP\Payment\Application\Command\Price\ModifyAvailableTo\Exception\WrongDateException;
use Konair\HAP\Payment\Domain\Model\Price\Exception\PricePlanDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Price\PricePlanRepository;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePlanId;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;

/**
 * @implements ApplicationService<ModifyAvailableToRequest>
 */
final class ModifyAvailableToService implements ApplicationService
{
    public function __construct(
        private PricePlanRepository $repository,
    ) {
    }

    /**
     * @throws WrongRequestTypeException
     * @throws PricePlanNotFoundException
     * @throws WrongDateException
     */
    public function execute(Request $request): ModifyAvailableToResponse
    {
        if (!$request instanceof ModifyAvailableToRequest) {
            throw new WrongRequestTypeException();
        }

        $pricePlanId = PricePlanId::create($request->pricePlanId());
        $date = CarbonImmutable::create(
            $request->availableToYear(),
            $request->availableToMonth(),
            $request->availableToDay(),
            $request->availableToHour(),
            $request->availableToMinute(),
        ) ?: throw new WrongDateException();

        try {
            $pricePlan = $this->repository->byId($pricePlanId);
        } catch (PricePlanDoesNotExistsException) {
            throw new PricePlanNotFoundException();
        }

        $pricePlan->changeAvailableTo($date);

        return ModifyAvailableToResponse::createFromPricePlan($pricePlan);
    }
}
