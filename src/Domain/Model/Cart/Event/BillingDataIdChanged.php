<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Cart\Event;

use Carbon\CarbonImmutable;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartId;
use Konair\HAP\Shared\Domain\Model\DomainEvent\DomainEvent;

final class BillingDataIdChanged implements DomainEvent
{
    private CarbonImmutable $occurredOn;

    public function __construct(
        private CartId $cartId,
        private BillingDataId|null $buyerBillingData,
    ) {
        $this->occurredOn = CarbonImmutable::now();
    }

    public function cartId(): CartId
    {
        return $this->cartId;
    }

    public function buyerBillingDataId(): BillingDataId|null
    {
        return $this->buyerBillingData;
    }

    public function occurredOn(): CarbonImmutable
    {
        return $this->occurredOn;
    }
}
