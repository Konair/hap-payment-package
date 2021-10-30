<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\ModifyAvailableFrom;

use Konair\HAP\Shared\Application\Contract\Request;

final class ModifyAvailableFromRequest implements Request
{
    public function __construct(
        private string $pricePlanId,
        private int $availableFromYear,
        private int $availableFromMonth,
        private int $availableFromDay,
        private int $availableFromHour,
        private int $availableFromMinute,
    ) {
    }

    public function pricePlanId(): string
    {
        return $this->pricePlanId;
    }

    public function availableFromYear(): int
    {
        return $this->availableFromYear;
    }

    public function availableFromMonth(): int
    {
        return $this->availableFromMonth;
    }

    public function availableFromDay(): int
    {
        return $this->availableFromDay;
    }

    public function availableFromHour(): int
    {
        return $this->availableFromHour;
    }

    public function availableFromMinute(): int
    {
        return $this->availableFromMinute;
    }
}
