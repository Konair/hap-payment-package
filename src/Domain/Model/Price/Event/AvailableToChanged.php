<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Price\Event;

use Carbon\CarbonImmutable;
use Konair\HAP\Shared\Domain\Model\DomainEvent\DomainEvent;

final class AvailableToChanged implements DomainEvent
{
    private CarbonImmutable $occurredOn;

    public function __construct(private CarbonImmutable|null $availableTo)
    {
        $this->occurredOn = CarbonImmutable::now();
    }

    public function availableTo(): CarbonImmutable|null
    {
        return $this->availableTo;
    }

    public function occurredOn(): CarbonImmutable
    {
        return $this->occurredOn;
    }
}
