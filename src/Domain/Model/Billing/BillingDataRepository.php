<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Billing;

use Konair\HAP\Payment\Domain\Model\Billing\Exception\BillingDataDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Payment\Domain\Service\Billing\Specification;

interface BillingDataRepository
{
    /**
     * @param BillingDataId $billingDataId
     * @return BillingData
     * @throws BillingDataDoesNotExistsException
     */
    public function byId(BillingDataId $billingDataId): BillingData;

    /**
     * @param Specification[] $specifications
     * @return BillingData[]
     */
    public function query(array $specifications): array;
}
