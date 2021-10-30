<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\ItemAccessPlan;

use Carbon\CarbonImmutable;
use Exception;
use PHPUnit\Framework\TestCase;

final class PeriodPlanTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testNotStartedPeriod(): void
    {
        // given
        CarbonImmutable::setTestNow('2019-01-01');
        $periodStartedAt = CarbonImmutable::create(2019, 03, 01) ?: throw new Exception();
        $periodFinishedAt = CarbonImmutable::create(2019, 04, 01) ?: throw new Exception();

        // when
        $plan = new PeriodicItemAccessPlan($periodStartedAt, $periodFinishedAt);

        // then
        $this->assertFalse($plan->hasAccess(null));
        $this->assertTrue($plan->canExpired());
        $this->assertSame($periodFinishedAt, $plan->expiredAt(null));
    }

    /**
     * @throws Exception
     */
    public function testAlreadyFinishedPeriod(): void
    {
        // given
        CarbonImmutable::setTestNow('2019-05-01');
        $periodStartedAt = CarbonImmutable::create(2019, 03, 01) ?: throw new Exception();
        $periodFinishedAt = CarbonImmutable::create(2019, 04, 01) ?: throw new Exception();

        // when
        $plan = new PeriodicItemAccessPlan($periodStartedAt, $periodFinishedAt);

        // then
        $this->assertFalse($plan->hasAccess(null));
        $this->assertTrue($plan->canExpired());
        $this->assertSame($periodFinishedAt, $plan->expiredAt(null));
    }

    /**
     * @throws Exception
     */
    public function testInPeriod(): void
    {
        // given
        CarbonImmutable::setTestNow('2019-03-10');
        $periodStartedAt = CarbonImmutable::create(2019, 03, 01) ?: throw new Exception();
        $periodFinishedAt = CarbonImmutable::create(2019, 04, 01) ?: throw new Exception();

        // when
        $plan = new PeriodicItemAccessPlan($periodStartedAt, $periodFinishedAt);

        // then
        $this->assertTrue($plan->hasAccess(null));
        $this->assertTrue($plan->canExpired());
        $this->assertEquals($periodFinishedAt, $plan->expiredAt(null));
    }

    /**
     * @throws Exception
     */
    public function testOnStartOfPeriod(): void
    {
        // given
        CarbonImmutable::setTestNow('2019-03-01');
        $periodStartedAt = CarbonImmutable::create(2019, 03, 01) ?: throw new Exception();
        $periodFinishedAt = CarbonImmutable::create(2019, 04, 01) ?: throw new Exception();

        // when
        $plan = new PeriodicItemAccessPlan($periodStartedAt, $periodFinishedAt);

        // then
        $this->assertTrue($plan->hasAccess(null));
        $this->assertTrue($plan->canExpired());
        $this->assertEquals($periodFinishedAt, $plan->expiredAt(null));
    }

    /**
     * @throws Exception
     */
    public function testOnEndOfPeriod(): void
    {
        // given
        CarbonImmutable::setTestNow('2019-04-01');
        $periodStartedAt = CarbonImmutable::create(2019, 03, 01) ?: throw new Exception();
        $periodFinishedAt = CarbonImmutable::create(2019, 04, 01) ?: throw new Exception();

        // when
        $plan = new PeriodicItemAccessPlan($periodStartedAt, $periodFinishedAt);

        // then
        $this->assertTrue($plan->hasAccess(null));
        $this->assertTrue($plan->canExpired());
        $this->assertEquals($periodFinishedAt, $plan->expiredAt(null));
    }
}
