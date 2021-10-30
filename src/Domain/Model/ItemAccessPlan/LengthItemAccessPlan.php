<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\ItemAccessPlan;

use Carbon\CarbonInterval;
use Carbon\CarbonImmutable;

final class LengthItemAccessPlan implements ItemAccessPlan
{
    private string $name = 'length';

    public function __construct(
        private CarbonInterval $accessInterval,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function accessInterval(): CarbonInterval
    {
        return $this->accessInterval;
    }

    public function hasAccess(CarbonImmutable|null $purchasedAt): bool
    {
        if (is_null($purchasedAt)) {
            return false;
        }

        $expirationDate = $purchasedAt->add($this->accessInterval);

        return $expirationDate >= CarbonImmutable::now();
    }

    public function canExpired(): bool
    {
        return true;
    }

    public function expiredAt(CarbonImmutable|null $purchasedAt): CarbonImmutable|null
    {
        return is_null($purchasedAt) ? null : $purchasedAt->add($this->accessInterval);
    }
}
