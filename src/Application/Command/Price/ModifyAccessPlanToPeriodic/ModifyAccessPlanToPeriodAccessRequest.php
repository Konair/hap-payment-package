<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToPeriodic;

use Konair\HAP\Shared\Application\Contract\Request;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
final class ModifyAccessPlanToPeriodAccessRequest implements Request
{
    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        private string $pricePlanId,
        private int $startedAtYear,
        private int $startedAtMonth,
        private int $startedAtDay,
        private int $startedAtHour,
        private int $startedAtMinute,
        private int $finishedAtYear,
        private int $finishedAtMonth,
        private int $finishedAtDay,
        private int $finishedAtHour,
        private int $finishedAtMinute,
    ) {
    }

    public function pricePlanId(): string
    {
        return $this->pricePlanId;
    }

    public function startedAtYear(): int
    {
        return $this->startedAtYear;
    }

    public function startedAtMonth(): int
    {
        return $this->startedAtMonth;
    }

    public function startedAtDay(): int
    {
        return $this->startedAtDay;
    }

    public function startedAtHour(): int
    {
        return $this->startedAtHour;
    }

    public function startedAtMinute(): int
    {
        return $this->startedAtMinute;
    }

    public function finishedAtYear(): int
    {
        return $this->finishedAtYear;
    }

    public function finishedAtMonth(): int
    {
        return $this->finishedAtMonth;
    }

    public function finishedAtDay(): int
    {
        return $this->finishedAtDay;
    }

    public function finishedAtHour(): int
    {
        return $this->finishedAtHour;
    }

    public function finishedAtMinute(): int
    {
        return $this->finishedAtMinute;
    }
}
