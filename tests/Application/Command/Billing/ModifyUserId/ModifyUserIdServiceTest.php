<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Billing\ModifyUserId;

use Exception;
use Konair\HAP\Payment\Domain\Model\Billing\BillingData;
use Konair\HAP\Payment\Domain\Model\Billing\BillingDataRepository;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\UserId;
use PHPUnit\Framework\TestCase;

final class ModifyUserIdServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testToModifyUserId(): void
    {
        // given
        $userId = '54de3f91-0e18-46d5-8f21-7df3dbe1b027';
        $billingDataId = '01024c1d-2f8a-4c68-8d71-60e76b7eb784';
        $billingData = new BillingData(BillingDataId::create($billingDataId));

        $repository = $this->getMockBuilder(BillingDataRepository::class)->getMock();
        $repository->method('byId')->willReturn($billingData);

        $request = new ModifyUserIdRequest($billingDataId, $userId);
        $service = new ModifyUserIdService($repository);

        // when
        $response = $service->execute($request);

        // then
        $this->assertIsString($response->billingDataId());
        $this->assertSame($userId, $response->userId());
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

    /**
     * @throws Exception
     */
    public function testToRemoveUserId(): void
    {
        // given
        $billingDataId = '6ddeae73-4b61-458a-8ff6-cff859d5b122';
        $billingData = new BillingData(BillingDataId::create($billingDataId));
        $billingData->changeUserId(UserId::create('c7280c53-491e-4bb0-b3f6-645f38775abc'));

        $repository = $this->getMockBuilder(BillingDataRepository::class)->getMock();
        $repository->method('byId')->willReturn($billingData);

        $request = new ModifyUserIdRequest($billingDataId, null);
        $service = new ModifyUserIdService($repository);

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
