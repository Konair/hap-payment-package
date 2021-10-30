<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\ModifyPrice;

use Exception;
use Konair\HAP\Payment\Domain\Model\Item\ValueObject\ItemId;
use Konair\HAP\Payment\Domain\Model\Price\PricePlan;
use Konair\HAP\Payment\Domain\Model\Price\PricePlanRepository;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePlanId;
use PHPUnit\Framework\TestCase;

final class ModifyPriceServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testToSetPrice(): void
    {
        // given
        $pricePlanId = '405da7c5-7f90-48bc-a5ca-65c9b5977235';
        $pricePlan = new PricePlan(
            PricePlanId::create($pricePlanId),
            ItemId::create('bd997950-b55a-49b1-a0ee-6d52d268a6e7'),
        );
        $priceGrossAmount = 100000;
        $priceCurrencyIsoCode = 'HUF';

        $repository = $this->getMockBuilder(PricePlanRepository::class)->getMock();
        $repository->method('byId')->willReturn($pricePlan);

        $request = new ModifyPriceRequest(
            $pricePlanId,
            $priceGrossAmount, // 1000.00
            $priceCurrencyIsoCode,
            'AAM',
        );
        $service = new ModifyPriceService($repository);

        // when
        $response = $service->execute($request);

        // then
        $this->assertSame($pricePlanId, $response->pricePlanId());
        $this->assertSame($priceGrossAmount, $response->priceGrossAmount());
        $this->assertSame($priceCurrencyIsoCode, $response->priceCurrencyIsoCode());
    }
}
