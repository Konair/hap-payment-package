<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Price\Event;

use Carbon\CarbonImmutable;
use Konair\HAP\Payment\Domain\Model\Price\Price;
use Konair\HAP\Shared\Domain\Model\DomainEvent\DomainEvent;

final class PriceChanged implements DomainEvent
{
    private CarbonImmutable $occurredOn;

    public function __construct(private Price|null $price)
    {
        $this->occurredOn = CarbonImmutable::now();
    }

    public function price(): Price|null
    {
        return $this->price;
    }

    public function occurredOn(): CarbonImmutable
    {
        return $this->occurredOn;
    }
}
