<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Billing\ModifyPhoneNumber;

use Exception;
use Konair\HAP\Payment\Domain\Model\Billing\BillingData;
use Konair\HAP\Payment\Domain\Model\Billing\BillingDataRepository;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\PhoneNumber;
use PHPUnit\Framework\TestCase;

final class ModifyPhoneNumberServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testToChangePhoneNumber(): void
    {
        // given
        $phoneNumber = '+41 12 123 4567';
        $billingDataId = 'f491a06f-b292-4d6a-8eb3-5da263180140';
        $billingData = new BillingData(BillingDataId::create($billingDataId));

        $repository = $this->getMockBuilder(BillingDataRepository::class)->getMock();
        $repository->method('byId')->willReturn($billingData);

        $request = new ModifyPhoneNumberRequest($billingDataId, $phoneNumber);
        $service = new ModifyPhoneNumberService($repository);

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
        $this->assertSame($phoneNumber, $response->phoneNumber());
    }

    /**
     * @throws Exception
     */
    public function testToRemovePhoneNumber(): void
    {
        // given
        $billingDataId = 'a41ec979-7b9e-4000-83be-87eaaf20dadb';
        $billingData = new BillingData(BillingDataId::create($billingDataId));
        $billingData->changePhoneNumber(PhoneNumber::create('+41 12 123 4567'));

        $repository = $this->getMockBuilder(BillingDataRepository::class)->getMock();
        $repository->method('byId')->willReturn($billingData);

        $request = new ModifyPhoneNumberRequest($billingDataId, null);
        $service = new ModifyPhoneNumberService($repository);

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
