<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Billing\ModifyFirmName;

use Exception;
use Konair\HAP\Payment\Domain\Model\Billing\BillingData;
use Konair\HAP\Payment\Domain\Model\Billing\BillingDataRepository;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\FirmName;
use PHPUnit\Framework\TestCase;

final class ModifyFirmNameServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testToChangeFirmName(): void
    {
        // given
        $firmName = 'MyCustomFirmName AG';
        $billingDataId = 'fbf6b15a-562f-4a9f-b207-584c21eff77c';
        $billingData = new BillingData(BillingDataId::create($billingDataId));

        $repository = $this->getMockBuilder(BillingDataRepository::class)->getMock();
        $repository->method('byId')->willReturn($billingData);

        $request = new ModifyFirmNameRequest($billingDataId, $firmName);
        $service = new ModifyFirmNameService($repository);

        // when
        $response = $service->execute($request);

        // then
        $this->assertIsString($response->billingDataId());
        $this->assertNull($response->userId());
        $this->assertNull($response->namePrefix());
        $this->assertNull($response->nameFirstName());
        $this->assertNull($response->nameLastName());
        $this->assertSame($firmName, $response->firmName());
        $this->assertNull($response->addressCountry());
        $this->assertNull($response->addressZip());
        $this->assertNull($response->addressCity());
        $this->assertNull($response->addressLine());
        $this->assertNull($response->phoneNumber());
    }

    /**
     * @throws Exception
     */
    public function testToRemoveFirmName(): void
    {
        // given
        $billingDataId = 'fbf6b15a-562f-4a9f-b207-584c21eff77c';
        $billingData = new BillingData(BillingDataId::create($billingDataId));
        $billingData->changeFirmName(FirmName::create('MyCustomFirmName AG'));

        $repository = $this->getMockBuilder(BillingDataRepository::class)->getMock();
        $repository->method('byId')->willReturn($billingData);

        $request = new ModifyFirmNameRequest($billingDataId, null);
        $service = new ModifyFirmNameService($repository);

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
