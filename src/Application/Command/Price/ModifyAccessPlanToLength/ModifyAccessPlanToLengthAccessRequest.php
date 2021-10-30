<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToLength;

use Konair\HAP\Shared\Application\Contract\Request;

final class ModifyAccessPlanToLengthAccessRequest implements Request
{
    public function __construct(
        private string $pricePlanId,
        private int $years = 0,
        private int $months = 0,
        private int $weeks = 0,
        private int $days = 0,
        private int $hours = 0,
        private int $minutes = 0,
        private int $seconds = 0,
    ) {
    }

    public function pricePlanId(): string
    {
        return $this->pricePlanId;
    }

    public function years(): int
    {
        return $this->years;
    }

    public function months(): int
    {
        return $this->months;
    }

    public function weeks(): int
    {
        return $this->weeks;
    }

    public function days(): int
    {
        return $this->days;
    }

    public function hours(): int
    {
        return $this->hours;
    }

    public function minutes(): int
    {
        return $this->minutes;
    }

    public function seconds(): int
    {
        return $this->seconds;
    }
}
