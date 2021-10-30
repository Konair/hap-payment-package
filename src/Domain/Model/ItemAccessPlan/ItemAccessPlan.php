<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\ItemAccessPlan;

use Carbon\CarbonImmutable;

interface ItemAccessPlan
{
    public function name(): string;

    public function hasAccess(CarbonImmutable|null $purchasedAt): bool;

    public function canExpired(): bool;

    public function expiredAt(CarbonImmutable|null $purchasedAt): CarbonImmutable|null;
}
