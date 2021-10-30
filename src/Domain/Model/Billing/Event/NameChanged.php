<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Billing\Event;

use Carbon\CarbonImmutable;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Shared\Domain\Model\DomainEvent\DomainEvent;
use Konair\HAP\Shared\Domain\Model\Name\ValueObject\Name;

final class NameChanged implements DomainEvent
{
    private CarbonImmutable $occurredOn;

    public function __construct(
        private BillingDataId $billingDataId,
        private Name|null $name,
    ) {
        $this->occurredOn = CarbonImmutable::now();
    }

    public function billingDataId(): BillingDataId
    {
        return $this->billingDataId;
    }

    public function name(): Name|null
    {
        return $this->name;
    }

    public function occurredOn(): CarbonImmutable
    {
        return $this->occurredOn;
    }
}
