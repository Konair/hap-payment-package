<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Domain\Model\Billing;

use Konair\HAP\Payment\Domain\Model\Billing\BillingData;
use Konair\HAP\Payment\Domain\Model\Billing\BillingDataRepository;
use Konair\HAP\Payment\Domain\Model\Billing\Event\BillingDataCreated;
use Konair\HAP\Payment\Domain\Model\Billing\Exception\BillingDataDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Payment\Domain\Service\Billing\Specification;
use Konair\HAP\Shared\Domain\Model\EventStore\EventStore;

final class EventStoreBillingDataRepository implements BillingDataRepository
{
    private EventStore $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function byId(BillingDataId $billingDataId): BillingData
    {
        $eventStream = $this->eventStore->allStoredEvent($billingDataId);

        if (count($eventStream->events()) === 0) {
            throw new BillingDataDoesNotExistsException();
        }

        return BillingData::reconstitute($eventStream);
    }

    /**
     * @return BillingData[]
     */
    public function all(): array
    {
        $events = $this->eventStore->byType(BillingDataCreated::class);

        return array_map(function (BillingDataCreated $event) {
            return $this->byId($event->billingDataId());
        }, [...$events]);
    }

    /**
     * @param Specification[] $specifications
     * @return BillingData[]
     */
    public function query(array $specifications): array
    {
        return []; // todo
    }
}
