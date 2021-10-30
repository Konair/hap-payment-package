<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\CreatePricePlan;

use Exception;
use PHPUnit\Framework\TestCase;

final class CreatePricePlanServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testToCreatePricePlan(): void
    {
        // given
        $itemId = 'fa8f3896-7803-4b4c-8d91-9c5aa6bf1917';
        $request = new CreatePricePlanRequest($itemId);
        $service = new CreatePricePlanService();

        // when
        $response = $service->execute($request);

        // then
        $this->assertIsString($response->pricePlanId());
    }
}
