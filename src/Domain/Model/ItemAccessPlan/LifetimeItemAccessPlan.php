<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\ItemAccessPlan;

use Carbon\CarbonImmutable;

final class LifetimeItemAccessPlan implements ItemAccessPlan
{
    private string $name = 'lifetime';

    public function name(): string
    {
        return $this->name;
    }

    public function hasAccess(CarbonImmutable|null $purchasedAt): bool
    {
        return $purchasedAt instanceof CarbonImmutable;
    }

    public function canExpired(): bool
    {
        return false;
    }

    public function expiredAt(CarbonImmutable|null $purchasedAt): CarbonImmutable|null
    {
        return null;
    }
}
