<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Query\Price\GetPricePlan;

use Konair\HAP\Payment\Application\Query\Price\GetPricePlan\Exception\PricePlanNotFoundException;
use Konair\HAP\Payment\Domain\Model\Price\Exception\PricePlanDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Price\PricePlanRepository;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePlanId;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;

/** @implements ApplicationService<GetPricePlanRequest> */
final class GetPricePlanService implements ApplicationService
{
    public function __construct(private PricePlanRepository $repository)
    {
    }

    /**
     * @throws PricePlanNotFoundException
     * @throws WrongRequestTypeException
     */
    public function execute(Request $request): GetPricePlanResponse
    {
        if (!$request instanceof GetPricePlanRequest) {
            throw new WrongRequestTypeException();
        }

        $pricePlanId = PricePlanId::create($request->pricePlanId());

        try {
            $pricePlan = $this->repository->byId($pricePlanId);
        } catch (PricePlanDoesNotExistsException) {
            throw new PricePlanNotFoundException();
        }

        return GetPricePlanResponse::createFromPricePlan($pricePlan);
    }
}
