<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToPeriodic;

use Carbon\CarbonImmutable;
use Exception;
use Konair\HAP\Payment\Domain\Model\Item\ValueObject\ItemId;
use Konair\HAP\Payment\Domain\Model\Price\PricePlan;
use Konair\HAP\Payment\Domain\Model\Price\PricePlanRepository;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePlanId;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.LongClassName)
 */
final class ModifyAccessPlanToPeriodAccessServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testToChangeAccessPlan(): void
    {
        // given
        $pricePlanId = '228095da-c4fd-4e9f-abe6-9678a9ff7d75';
        $startedAtYear = CarbonImmutable::create(2021) ?: throw new Exception();
        $finishedAtYear = CarbonImmutable::create(2023) ?: throw new Exception();
        $pricePlan = new PricePlan(
            PricePlanId::create($pricePlanId),
            ItemId::create('b899175d-4e38-49ae-8e4f-29eca8946b75'),
        );

        $repository = $this->getMockBuilder(PricePlanRepository::class)->getMock();
        $repository->method('byId')->willReturn($pricePlan);

        $request = new ModifyAccessPlanToPeriodAccessRequest(
            $pricePlanId,
            $startedAtYear->year,
            $startedAtYear->month,
            $startedAtYear->day,
            $startedAtYear->hour,
            $startedAtYear->minute,
            $finishedAtYear->year,
            $finishedAtYear->month,
            $finishedAtYear->day,
            $finishedAtYear->hour,
            $finishedAtYear->minute,
        );
        $service = new ModifyAccessPlanToPeriodAccessService($repository);

        // when
        $response = $service->execute($request);

        // then
        $this->assertSame($pricePlanId, $response->pricePlanId());
        $this->assertSame('periodic', $response->accessPlan());
        $this->assertInstanceOf(CarbonImmutable::class, $response->periodicItemAccessPlanStartedAt());
        $this->assertInstanceOf(CarbonImmutable::class, $response->periodicItemAccessPlanFinishedAt());
        $this->assertTrue($response->periodicItemAccessPlanStartedAt()->equalTo($startedAtYear));
        $this->assertTrue($response->periodicItemAccessPlanFinishedAt()->equalTo($finishedAtYear));
    }
}
