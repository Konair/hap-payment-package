<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Query\Price\GetPricePlan;

use Konair\HAP\Shared\Application\Contract\Request;

final class GetPricePlanRequest implements Request
{
    public function __construct(private string $pricePlanId)
    {
    }

    public function pricePlanId(): string
    {
        return $this->pricePlanId;
    }
}
