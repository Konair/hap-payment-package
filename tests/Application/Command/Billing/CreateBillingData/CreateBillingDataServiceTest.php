<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Billing\CreateBillingData;

use Exception;
use PHPUnit\Framework\TestCase;

final class CreateBillingDataServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testToCreateNewBillingData(): void
    {
        // given
        $request = new CreateBillingDataRequest();
        $service = new CreateBillingDataService();

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
