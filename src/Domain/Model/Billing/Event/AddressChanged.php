<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Billing\Event;

use Carbon\CarbonImmutable;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Shared\Domain\Model\Address\ValueObject\Address;
use Konair\HAP\Shared\Domain\Model\DomainEvent\DomainEvent;

final class AddressChanged implements DomainEvent
{
    private CarbonImmutable $occurredOn;

    public function __construct(
        private BillingDataId $billingDataId,
        private Address|null $address,
    ) {
        $this->occurredOn = CarbonImmutable::now();
    }

    public function billingDataId(): BillingDataId
    {
        return $this->billingDataId;
    }

    public function address(): Address|null
    {
        return $this->address;
    }

    public function occurredOn(): CarbonImmutable
    {
        return $this->occurredOn;
    }
}
