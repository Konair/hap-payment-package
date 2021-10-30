<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Price;

use Konair\HAP\Payment\Domain\Model\Price\Exception\PricePlanDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePlanId;

interface PricePlanRepository
{
    /**
     * @param PricePlanId $pricePlanId
     * @return PricePlan
     * @throws PricePlanDoesNotExistsException
     */
    public function byId(PricePlanId $pricePlanId): PricePlan;
}
