<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\CreatePricePlan;

use Konair\HAP\Payment\Domain\Model\Item\ValueObject\ItemId;
use Konair\HAP\Payment\Domain\Model\Price\PricePlan;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;

/**
 * @implements ApplicationService<CreatePricePlanRequest>
 */
final class CreatePricePlanService implements ApplicationService
{
    /**
     * @throws WrongRequestTypeException
     */
    public function execute(Request $request): CreatePricePlanResponse
    {
        if (!$request instanceof CreatePricePlanRequest) {
            throw new WrongRequestTypeException();
        }

        $itemId = ItemId::create($request->itemId());

        $pricePlan = PricePlan::create($itemId);

        return CreatePricePlanResponse::createFromPricePlan($pricePlan);
    }
}
