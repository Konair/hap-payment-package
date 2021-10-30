<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Billing\Event;

use Carbon\CarbonImmutable;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Shared\Domain\Model\DomainEvent\DomainEvent;

final class BillingDataCreated implements DomainEvent
{
    private CarbonImmutable $occurredOn;

    public function __construct(
        private BillingDataId $billingDataId,
    ) {
        $this->occurredOn = CarbonImmutable::now();
    }

    public function billingDataId(): BillingDataId
    {
        return $this->billingDataId;
    }

    public function occurredOn(): CarbonImmutable
    {
        return $this->occurredOn;
    }
}
