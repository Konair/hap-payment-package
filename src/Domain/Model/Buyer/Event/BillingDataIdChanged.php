<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Buyer\Event;

use Carbon\CarbonImmutable;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Payment\Domain\Model\Buyer\ValueObject\BuyerId;
use Konair\HAP\Shared\Domain\Model\DomainEvent\DomainEvent;

final class BillingDataIdChanged implements DomainEvent
{
    private CarbonImmutable $occurredOn;

    public function __construct(
        private BuyerId $buyerId,
        private BillingDataId|null $billingDataId,
    ) {
        $this->occurredOn = CarbonImmutable::now();
    }

    public function buyerId(): BuyerId
    {
        return $this->buyerId;
    }

    public function billingDataId(): BillingDataId|null
    {
        return $this->billingDataId;
    }

    public function occurredOn(): CarbonImmutable
    {
        return $this->occurredOn;
    }
}
