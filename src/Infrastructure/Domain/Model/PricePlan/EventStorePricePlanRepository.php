<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Domain\Model\PricePlan;

use Konair\HAP\Payment\Domain\Model\Price\Exception\PricePlanDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Price\PricePlan;
use Konair\HAP\Payment\Domain\Model\Price\PricePlanRepository;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePlanId;
use Konair\HAP\Shared\Domain\Model\EventStore\EventStore;

final class EventStorePricePlanRepository implements PricePlanRepository
{
    private EventStore $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function byId(PricePlanId $pricePlanId): PricePlan
    {
        $eventStream = $this->eventStore->allStoredEvent($pricePlanId);

        if (count($eventStream->events()) === 0) {
            throw new PricePlanDoesNotExistsException();
        }

        return PricePlan::reconstitute($eventStream);
    }
}
