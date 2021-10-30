<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\ItemAccessPlan;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Exception;
use PHPUnit\Framework\TestCase;

final class LengthPlanTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testExpiredDate(): void
    {
        // given
        CarbonImmutable::setTestNow('2019-10-01');

        $purchasedAt = CarbonImmutable::create(2019, 01, 01) ?: throw new Exception();
        $expirationDate = CarbonImmutable::create(2019, 01, 07) ?: throw new Exception();
        $accessInterval = CarbonInterval::create(0, 0, 0, 6);

        // when
        $plan = new LengthItemAccessPlan($accessInterval);

        // then
        $this->assertFalse($plan->hasAccess($purchasedAt));
        $this->assertTrue($plan->canExpired());
        $this->assertEquals($expirationDate, $plan->expiredAt($purchasedAt));
    }

    /**
     * @throws Exception
     */
    public function testInLength(): void
    {
        // given
        CarbonImmutable::setTestNow('2019-01-03');

        $purchasedAt = CarbonImmutable::create(2019, 01, 01) ?: throw new Exception();
        $expirationDate = CarbonImmutable::create(2019, 01, 07) ?: throw new Exception();
        $accessInterval = CarbonInterval::create(0, 0, 0, 6);

        $plan = new LengthItemAccessPlan($accessInterval);

        $this->assertTrue($plan->hasAccess($purchasedAt));
        $this->assertTrue($plan->canExpired());
        $this->assertEquals($expirationDate, $plan->expiredAt($purchasedAt));
    }

    /**
     * @throws Exception
     */
    public function testOnStartOfLength(): void
    {
        // given
        CarbonImmutable::setTestNow('2019-01-01');

        $purchasedAt = CarbonImmutable::create(2019, 01, 01) ?: throw new Exception();
        $expirationDate = CarbonImmutable::create(2019, 01, 07) ?: throw new Exception();
        $accessInterval = CarbonInterval::create(0, 0, 0, 6);

        $plan = new LengthItemAccessPlan($accessInterval);

        $this->assertTrue($plan->hasAccess($purchasedAt));
        $this->assertTrue($plan->canExpired());
        $this->assertEquals($expirationDate, $plan->expiredAt($purchasedAt));
    }
}
