<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Price\Event;

use Carbon\CarbonImmutable;
use Konair\HAP\Payment\Domain\Model\Item\ValueObject\ItemId;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePlanId;
use Konair\HAP\Shared\Domain\Model\DomainEvent\DomainEvent;

final class PricePlanCreated implements DomainEvent
{
    private CarbonImmutable $occurredOn;

    public function __construct(
        private PricePlanId $pricePlanId,
        private ItemId $itemId,
    ) {
        $this->occurredOn = CarbonImmutable::now();
    }

    public function pricePlanId(): PricePlanId
    {
        return $this->pricePlanId;
    }

    public function itemId(): ItemId
    {
        return $this->itemId;
    }

    public function occurredOn(): CarbonImmutable
    {
        return $this->occurredOn;
    }
}
