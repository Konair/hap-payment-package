<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Billing\ModifyAddress;

use Exception;
use Konair\HAP\Payment\Domain\Model\Billing\BillingData;
use Konair\HAP\Payment\Domain\Model\Billing\BillingDataRepository;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Shared\Domain\Model\Address\ValueObject\Address;
use Konair\HAP\Shared\Domain\Model\Address\ValueObject\City;
use Konair\HAP\Shared\Domain\Model\Address\ValueObject\Country;
use Konair\HAP\Shared\Domain\Model\Address\ValueObject\Line;
use Konair\HAP\Shared\Domain\Model\Address\ValueObject\Zip;
use PHPUnit\Framework\TestCase;

final class ModifyAddressTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testToChangeAddress(): void
    {
        // given
        $billingDataId = '66328b7a-ef76-440c-b364-4636b9338f0c';
        $addressCountry = 'Switzerland';
        $addressZip = '3008';
        $addressCity = 'Bern';
        $addressLine = 'Nowhere street 404';
        $billingData = new BillingData(BillingDataId::create($billingDataId));

        $repository = $this->getMockBuilder(BillingDataRepository::class)->getMock();
        $repository->method('byId')->willReturn($billingData);

        $request = new ModifyAddressRequest(
            $billingDataId,
            $addressCountry,
            $addressZip,
            $addressCity,
            $addressLine,
        );
        $service = new ModifyAddressService($repository);

        // when
        $response = $service->execute($request);

        // then
        $this->assertIsString($response->billingDataId());
        $this->assertNull($response->userId());
        $this->assertNull($response->namePrefix());
        $this->assertNull($response->nameFirstName());
        $this->assertNull($response->nameLastName());
        $this->assertNull($response->firmName());
        $this->assertSame($addressCountry, $response->addressCountry());
        $this->assertSame($addressZip, $response->addressZip());
        $this->assertSame($addressCity, $response->addressCity());
        $this->assertSame($addressLine, $response->addressLine());
        $this->assertNull($response->phoneNumber());
    }

    /**
     * @throws Exception
     */
    public function testToRemoveAddress(): void
    {
        // given
        $billingDataId = 'f6d2fea8-6109-4827-bd1a-e17e01ba4724';
        $billingData = new BillingData(BillingDataId::create($billingDataId));
        $billingData->changeAddress(
            Address::create(
                Country::create('Switzerland'),
                Zip::create('3008'),
                City::create('Bern'),
                Line::create('Nowhere street 404'),
            ),
        );

        $repository = $this->getMockBuilder(BillingDataRepository::class)->getMock();
        $repository->method('byId')->willReturn($billingData);

        $request = new ModifyAddressRequest(
            $billingDataId,
            null,
            null,
            null,
            null,
        );
        $service = new ModifyAddressService($repository);

        // when
        $response = $service->execute($request);

        // then
        $this->assertIsString($response->billingDataId());
        $this->assertNull($response->userId());
        $this->assertNull($response->namePrefix());
        $this->assertNull($response->nameFirstName());
        $this->assertNull($response->nameLastName());
        $this->assertNull($response->firmName());
        $this->assertNull($response->addressCountry());
        $this->assertNull($response->addressZip());
        $this->assertNull($response->addressCity());
        $this->assertNull($response->addressLine());
        $this->assertNull($response->phoneNumber());
    }
}
