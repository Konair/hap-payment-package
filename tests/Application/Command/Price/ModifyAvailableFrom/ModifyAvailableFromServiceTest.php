<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\ModifyAvailableFrom;

use Carbon\CarbonImmutable;
use Exception;
use Konair\HAP\Payment\Domain\Model\Item\ValueObject\ItemId;
use Konair\HAP\Payment\Domain\Model\Price\PricePlan;
use Konair\HAP\Payment\Domain\Model\Price\PricePlanRepository;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePlanId;
use PHPUnit\Framework\TestCase;

final class ModifyAvailableFromServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testToChangeDate(): void
    {
        // given
        $pricePlanId = 'd78267d3-130f-499c-8b49-88a468c98b41';
        $availableFrom = CarbonImmutable::create(2021) ?: throw new Exception();
        $pricePlan = new PricePlan(
            PricePlanId::create($pricePlanId),
            ItemId::create('18c983bf-1ddf-4d7e-a6b0-d84c40e9567a'),
        );

        $repository = $this->getMockBuilder(PricePlanRepository::class)->getMock();
        $repository->method('byId')->willReturn($pricePlan);

        $request = new ModifyAvailableFromRequest(
            $pricePlanId,
            $availableFrom->year,
            $availableFrom->month,
            $availableFrom->day,
            $availableFrom->hour,
            $availableFrom->minute,
        );
        $service = new ModifyAvailableFromService($repository);

        // when
        $response = $service->execute($request);

        // then
        $this->assertSame($pricePlanId, $response->pricePlanId());
        $this->assertInstanceOf(CarbonImmutable::class, $response->availableFrom());
        $this->assertTrue($response->availableFrom()->equalTo($availableFrom));
    }
}
