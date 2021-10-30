<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Billing\Event;

use Carbon\CarbonImmutable;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\PhoneNumber;
use Konair\HAP\Shared\Domain\Model\DomainEvent\DomainEvent;

final class PhoneNumberChanged implements DomainEvent
{
    private CarbonImmutable $occurredOn;

    public function __construct(
        private BillingDataId $billingDataId,
        private PhoneNumber|null $phoneNumber,
    ) {
        $this->occurredOn = CarbonImmutable::now();
    }

    public function billingDataId(): BillingDataId
    {
        return $this->billingDataId;
    }

    public function phoneNumber(): PhoneNumber|null
    {
        return $this->phoneNumber;
    }

    public function occurredOn(): CarbonImmutable
    {
        return $this->occurredOn;
    }
}
