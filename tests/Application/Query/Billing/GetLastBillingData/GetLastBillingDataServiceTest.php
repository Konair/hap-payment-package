<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Query\Billing\GetLastBillingData;

use Exception;
use Konair\HAP\Payment\Domain\Model\Billing\BillingData;
use Konair\HAP\Payment\Domain\Model\Billing\BillingDataRepository;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Payment\Domain\Service\Billing\Specification;
use Konair\HAP\Payment\Domain\Service\Billing\SpecificationFactory;
use PHPUnit\Framework\TestCase;

final class GetLastBillingDataServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testToGetLastBillingData(): void
    {
        // given
        $userId = 'c35b85d7-8ac0-47c8-bde7-e5ed1a3c7ffc';
        $billingDataId = '297537fd-d7f9-4eb3-9cf7-02d09caf69b2';
        $billingData = new BillingData(BillingDataId::create($billingDataId));

        $repository = $this->getMockBuilder(BillingDataRepository::class)->getMock();
        $repository->method('query')->willReturn([$billingData]);

        $specification = $this->getMockBuilder(Specification::class)->getMock();

        $factory = $this->getMockBuilder(SpecificationFactory::class)->getMock();
        $factory->method('lastOfUser')->willReturn($specification);

        $request = new GetLastBillingDataRequest($userId);
        $service = new GetLastBillingDataService($repository, $factory);

        // when
        $response = $service->execute($request);

        //then
        $this->assertSame($billingDataId, $response->billingDataId());
    }
}
