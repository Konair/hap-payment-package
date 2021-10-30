<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\ItemAccessPlan;

use Carbon\CarbonImmutable;

final class PeriodicItemAccessPlan implements ItemAccessPlan
{
    private string $name = 'periodic';

    public function __construct(
        private CarbonImmutable $periodStartedAt,
        private CarbonImmutable $periodFinishedAt,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function periodStartedAt(): CarbonImmutable
    {
        return $this->periodStartedAt;
    }

    public function periodFinishedAt(): CarbonImmutable
    {
        return $this->periodFinishedAt;
    }

    public function hasAccess(CarbonImmutable|null $purchasedAt): bool
    {
        $now = CarbonImmutable::now();

        return $this->periodStartedAt <= $now && $now <= $this->periodFinishedAt;
    }

    public function canExpired(): bool
    {
        return true;
    }

    public function expiredAt(CarbonImmutable|null $purchasedAt): CarbonImmutable|null
    {
        return $this->periodFinishedAt;
    }
}
