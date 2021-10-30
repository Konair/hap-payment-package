<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Price\Event;

use Carbon\CarbonImmutable;
use Konair\HAP\Payment\Domain\Model\ItemAccessPlan\ItemAccessPlan;
use Konair\HAP\Shared\Domain\Model\DomainEvent\DomainEvent;

final class AccessPlanChanged implements DomainEvent
{
    private CarbonImmutable $occurredOn;

    public function __construct(private ItemAccessPlan|null $accessPlan)
    {
        $this->occurredOn = CarbonImmutable::now();
    }

    public function accessPlan(): ItemAccessPlan|null
    {
        return $this->accessPlan;
    }

    public function occurredOn(): CarbonImmutable
    {
        return $this->occurredOn;
    }
}
