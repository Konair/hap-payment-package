<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\ModifyAccessPlanToLifetime;

use Exception;
use Konair\HAP\Payment\Domain\Model\Item\ValueObject\ItemId;
use Konair\HAP\Payment\Domain\Model\Price\PricePlan;
use Konair\HAP\Payment\Domain\Model\Price\PricePlanRepository;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePlanId;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.LongClassName)
 */
final class ModifyAccessPlanToLifetimeAccessServiceTest extends TestCase
{
    /** @throws Exception */
    public function testToChangeAccessPlan(): void
    {
        // given
        $pricePlanId = '0eb3ba48-6298-4378-b5b0-c0fe3a35e797';
        $pricePlan = new PricePlan(
            PricePlanId::create($pricePlanId),
            ItemId::create('de1aeb41-a7d7-465c-b3d9-d633f564e519'),
        );

        $repository = $this->getMockBuilder(PricePlanRepository::class)->getMock();
        $repository->method('byId')->willReturn($pricePlan);

        $request = new ModifyAccessPlanToLifetimeAccessRequest($pricePlanId);
        $service = new ModifyAccessPlanToLifetimeAccessService($repository);

        // when
        $response = $service->execute($request);

        // then
        $this->assertSame($pricePlanId, $response->pricePlanId());
        $this->assertSame('lifetime', $response->accessPlan());
    }
}
