<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\ItemAccessPlan;

use Carbon\CarbonImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

final class LifetimePlanTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testLifetimeWithPurchase(): void
    {
        // given
        $purchasedAt = CarbonImmutable::create(2019, 01, 01) ?: throw new Exception();

        // when
        $plan = new LifetimeItemAccessPlan();

        // then
        $this->assertTrue($plan->hasAccess($purchasedAt));
        $this->assertFalse($plan->canExpired());
        $this->assertNull($plan->expiredAt($purchasedAt));
    }

    public function testLifetimeWithoutPurchase(): void
    {
        // given
        // when
        $plan = new LifetimeItemAccessPlan();

        // then
        $this->assertFalse($plan->hasAccess(null));
        $this->assertFalse($plan->canExpired());
        $this->assertNull($plan->expiredAt(null));
    }
}
