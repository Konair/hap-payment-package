<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToLifetime;

use Konair\HAP\Shared\Application\Contract\Request;

final class ModifyAccessPlanToLifetimeAccessRequest implements Request
{
    public function __construct(
        private string $pricePlanId,
    ) {
    }

    public function pricePlanId(): string
    {
        return $this->pricePlanId;
    }
}
