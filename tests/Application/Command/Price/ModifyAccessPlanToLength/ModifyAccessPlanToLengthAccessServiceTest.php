<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToLength;

use Carbon\CarbonInterval;
use Exception;
use Konair\HAP\Payment\Domain\Model\Item\ValueObject\ItemId;
use Konair\HAP\Payment\Domain\Model\Price\PricePlan;
use Konair\HAP\Payment\Domain\Model\Price\PricePlanRepository;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePlanId;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.LongClassName)
 */
final class ModifyAccessPlanToLengthAccessServiceTest extends TestCase
{
    /** @throws Exception */
    public function testToChangeAccessPlan(): void
    {
        // given
        $accessPlanName = 'length';
        $pricePlanId = 'b2c9f9e7-aa0f-4c67-a6e5-6808e2c3a39b';
        $years = 100;
        $pricePlan = new PricePlan(
            PricePlanId::create($pricePlanId),
            ItemId::create('761c2bc9-ce90-46b5-b41f-3e2e3b364ea4'),
        );

        $repository = $this->getMockBuilder(PricePlanRepository::class)->getMock();
        $repository->method('byId')->willReturn($pricePlan);

        $request = new ModifyAccessPlanToLengthAccessRequest($pricePlanId, $years);
        $service = new ModifyAccessPlanToLengthAccessService($repository);

        // when
        $response = $service->execute($request);

        // then
        $this->assertSame($pricePlanId, $response->pricePlanId());
        $this->assertSame($accessPlanName, $response->accessPlan());
        $this->assertInstanceOf(CarbonInterval::class, $response->lengthAccessPlanInterval());
        $this->assertTrue($response->lengthAccessPlanInterval()->equalTo(CarbonInterval::create($years)));
    }
}
