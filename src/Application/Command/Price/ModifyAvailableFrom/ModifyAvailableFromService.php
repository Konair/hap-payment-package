<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\ModifyAvailableFrom;

use Carbon\CarbonImmutable;
use Konair\HAP\Payment\Application\Command\Price\ModifyAvailableFrom\Exception\PricePlanNotFoundException;
use Konair\HAP\Payment\Application\Command\Price\ModifyAvailableFrom\Exception\WrongDateException;
use Konair\HAP\Payment\Domain\Model\Price\Exception\PricePlanDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Price\PricePlanRepository;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePlanId;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;

/**
 * @implements ApplicationService<ModifyAvailableFromRequest>
 */
final class ModifyAvailableFromService implements ApplicationService
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
    public function execute(Request $request): ModifyAvailableFromResponse
    {
        if (!$request instanceof ModifyAvailableFromRequest) {
            throw new WrongRequestTypeException();
        }

        $pricePlanId = PricePlanId::create($request->pricePlanId());
        $date = CarbonImmutable::create(
            $request->availableFromYear(),
            $request->availableFromMonth(),
            $request->availableFromDay(),
            $request->availableFromHour(),
            $request->availableFromMinute(),
        ) ?: throw new WrongDateException();

        try {
            $pricePlan = $this->repository->byId($pricePlanId);
        } catch (PricePlanDoesNotExistsException) {
            throw new PricePlanNotFoundException();
        }

        $pricePlan->changeAvailableFrom($date);

        return ModifyAvailableFromResponse::createFromPricePlan($pricePlan);
    }
}
