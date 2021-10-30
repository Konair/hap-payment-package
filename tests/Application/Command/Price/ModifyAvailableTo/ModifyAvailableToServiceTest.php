<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\ModifyAvailableTo;

use Carbon\CarbonImmutable;
use Exception;
use Konair\HAP\Payment\Domain\Model\Item\ValueObject\ItemId;
use Konair\HAP\Payment\Domain\Model\Price\PricePlan;
use Konair\HAP\Payment\Domain\Model\Price\PricePlanRepository;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePlanId;
use PHPUnit\Framework\TestCase;

final class ModifyAvailableToServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testToChangeDate(): void
    {
        // given
        $pricePlanId = '2040aa2f-9040-42ac-b428-313f1a85cde6';
        $availableTo = CarbonImmutable::create(2023) ?: throw new Exception();
        $pricePlan = new PricePlan(
            PricePlanId::create($pricePlanId),
            ItemId::create('1cf05960-2617-4c6d-84bc-332b824d9e86'),
        );

        $repository = $this->getMockBuilder(PricePlanRepository::class)->getMock();
        $repository->method('byId')->willReturn($pricePlan);

        $request = new ModifyAvailableToRequest(
            $pricePlanId,
            $availableTo->year,
            $availableTo->month,
            $availableTo->day,
            $availableTo->hour,
            $availableTo->minute,
        );
        $service = new ModifyAvailableToService($repository);

        // when
        $response = $service->execute($request);

        // then
        $this->assertSame($pricePlanId, $response->pricePlanId());
        $this->assertInstanceOf(CarbonImmutable::class, $response->availableTo());
        $this->assertTrue($response->availableTo()->equalTo($availableTo));
    }
}
