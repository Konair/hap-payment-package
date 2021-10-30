<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Price\Event;

use Carbon\CarbonImmutable;
use Konair\HAP\Shared\Domain\Model\DomainEvent\DomainEvent;

final class AvailableFromChanged implements DomainEvent
{
    private CarbonImmutable $occurredOn;

    public function __construct(private CarbonImmutable|null $availableFrom)
    {
        $this->occurredOn = CarbonImmutable::now();
    }

    public function availableFrom(): CarbonImmutable|null
    {
        return $this->availableFrom;
    }

    public function occurredOn(): CarbonImmutable
    {
        return $this->occurredOn;
    }
}
