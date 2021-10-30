<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\CreatePricePlan;

use Konair\HAP\Shared\Application\Contract\Request;

final class CreatePricePlanRequest implements Request
{
    public function __construct(private string $itemId)
    {
    }

    public function itemId(): string
    {
        return $this->itemId;
    }
}
