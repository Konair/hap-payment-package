<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Cart\Event;

use Carbon\CarbonImmutable;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartId;
use Konair\HAP\Shared\Domain\Model\DomainEvent\DomainEvent;

final class CartCreated implements DomainEvent
{
    private CarbonImmutable $occurredOn;

    public function __construct(private CartId $cartId)
    {
        $this->occurredOn = CarbonImmutable::now();
    }

    public function cartId(): CartId
    {
        return $this->cartId;
    }

    public function occurredOn(): CarbonImmutable
    {
        return $this->occurredOn;
    }
}
