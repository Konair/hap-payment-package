<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Cart\Event;

use Carbon\CarbonImmutable;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartId;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartItemId;
use Konair\HAP\Shared\Domain\Model\DomainEvent\DomainEvent;

final class CartItemRemoved implements DomainEvent
{
    private CarbonImmutable $occurredOn;

    public function __construct(
        private CartId $cartId,
        private CartItemId $cartItemId,
    ) {
        $this->occurredOn = CarbonImmutable::now();
    }

    public function cartId(): CartId
    {
        return $this->cartId;
    }

    public function cartItemId(): CartItemId
    {
        return $this->cartItemId;
    }

    public function occurredOn(): CarbonImmutable
    {
        return $this->occurredOn;
    }
}
