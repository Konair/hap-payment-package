<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Cart\Event;

use Carbon\CarbonImmutable;
use Konair\HAP\Payment\Domain\Model\Buyer\ValueObject\BuyerId;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartId;
use Konair\HAP\Shared\Domain\Model\DomainEvent\DomainEvent;

final class BuyerIdChanged implements DomainEvent
{
    private CarbonImmutable $occurredOn;

    public function __construct(
        private CartId $cartId,
        private BuyerId|null $buyerId,
    ) {
        $this->occurredOn = CarbonImmutable::now();
    }

    public function cartId(): CartId
    {
        return $this->cartId;
    }

    public function buyerId(): BuyerId|null
    {
        return $this->buyerId;
    }

    public function occurredOn(): CarbonImmutable
    {
        return $this->occurredOn;
    }
}
