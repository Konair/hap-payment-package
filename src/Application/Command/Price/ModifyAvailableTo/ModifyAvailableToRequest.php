<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\ModifyAvailableTo;

use Konair\HAP\Shared\Application\Contract\Request;

final class ModifyAvailableToRequest implements Request
{
    public function __construct(
        private string $pricePlanId,
        private int $availableToYear,
        private int $availableToMonth,
        private int $availableToDay,
        private int $availableToHour,
        private int $availableToMinute,
    ) {
    }

    public function pricePlanId(): string
    {
        return $this->pricePlanId;
    }

    public function availableToYear(): int
    {
        return $this->availableToYear;
    }

    public function availableToMonth(): int
    {
        return $this->availableToMonth;
    }

    public function availableToDay(): int
    {
        return $this->availableToDay;
    }

    public function availableToHour(): int
    {
        return $this->availableToHour;
    }

    public function availableToMinute(): int
    {
        return $this->availableToMinute;
    }
}
