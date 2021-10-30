<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Billing\ModifyName;

use Exception;
use Konair\HAP\Payment\Domain\Model\Billing\BillingData;
use Konair\HAP\Payment\Domain\Model\Billing\BillingDataRepository;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Shared\Domain\Model\Name\ValueObject\FirstName;
use Konair\HAP\Shared\Domain\Model\Name\ValueObject\LastName;
use Konair\HAP\Shared\Domain\Model\Name\ValueObject\Name;
use PHPUnit\Framework\TestCase;

final class ModifyNameServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testToChangeName(): void
    {
        // given
        $firstName = 'John';
        $lastName = 'Doe';
        $billingDataId = 'c44f4d4c-332c-4c6f-9490-6c5338eff0a2';
        $billingData = new BillingData(BillingDataId::create($billingDataId));

        $repository = $this->getMockBuilder(BillingDataRepository::class)->getMock();
        $repository->method('byId')->willReturn($billingData);

        $request = new ModifyNameRequest($billingDataId, $firstName, $lastName);
        $service = new ModifyNameService($repository);

        // when
        $response = $service->execute($request);

        // then
        $this->assertIsString($response->billingDataId());
        $this->assertNull($response->userId());
        $this->assertNull($response->namePrefix());
        $this->assertSame($firstName, $response->nameFirstName());
        $this->assertSame($lastName, $response->nameLastName());
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
    public function testToRemoveName(): void
    {
        // given
        $billingDataId = '630f69fc-2d2d-4ddb-803a-9184e11a7a2f';
        $billingData = new BillingData(BillingDataId::create($billingDataId));
        $billingData->changeName(Name::create(
            null,
            FirstName::create('John'),
            LastName::create('Doe'),
        ));

        $repository = $this->getMockBuilder(BillingDataRepository::class)->getMock();
        $repository->method('byId')->willReturn($billingData);

        $request = new ModifyNameRequest($billingDataId, null, null);
        $service = new ModifyNameService($repository);

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
