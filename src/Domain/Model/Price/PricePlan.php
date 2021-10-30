<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Price;

use Carbon\CarbonImmutable;
use Konair\HAP\Payment\Domain\Model\Item\ValueObject\ItemId;
use Konair\HAP\Payment\Domain\Model\ItemAccessPlan\ItemAccessPlan;
use Konair\HAP\Payment\Domain\Model\Price\Event\AccessPlanChanged;
use Konair\HAP\Payment\Domain\Model\Price\Event\AvailableFromChanged;
use Konair\HAP\Payment\Domain\Model\Price\Event\AvailableToChanged;
use Konair\HAP\Payment\Domain\Model\Price\Event\PriceChanged;
use Konair\HAP\Payment\Domain\Model\Price\Event\PricePlanCreated;
use Konair\HAP\Payment\Domain\Model\Price\Exception\WrongFirstEventException;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePlanId;
use Konair\HAP\Shared\Domain\Model\Entity\AggregateRoot;
use Konair\HAP\Shared\Domain\Model\Entity\Exception\WrongIdentificationTypeException;
use Konair\HAP\Shared\Domain\Model\EventStore\EventStream;

/**
 * @extends AggregateRoot<PricePlanId>
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class PricePlan extends AggregateRoot
{
    private ItemAccessPlan|null $itemAccessPlan = null;
    private Price|null $price = null;
    private CarbonImmutable|null $availableFrom = null;
    private CarbonImmutable|null $availableTo = null;

    public static function create(
        ItemId $itemId,
    ): self {
        $pricePlanId = PricePlanId::create();
        $pricePlan = new self($pricePlanId, $itemId);
        $pricePlan->recordApplyAndPublishThat(new PricePlanCreated($pricePlanId, $itemId));

        return $pricePlan;
    }

    public static function reconstitute(EventStream $history): self
    {
        $pricePlanId = $history->aggregateId();

        if (!$pricePlanId instanceof PricePlanId) {
            throw new WrongIdentificationTypeException();
        }

        $firstEvent = $history->events()->offsetGet(0);

        if (!$firstEvent instanceof PricePlanCreated) {
            throw new WrongFirstEventException();
        }

        $pricePlan = new self($pricePlanId, $firstEvent->itemId());

        foreach ($history->events() as $event) {
            $pricePlan->applyThat($event);
        }

        return $pricePlan;
    }

    public function __construct(
        private PricePlanId $identification,
        private ItemId $itemId,
    ) {
    }

    // getters

    public function identification(): PricePlanId
    {
        return $this->identification;
    }

    public function itemId(): ItemId
    {
        return $this->itemId;
    }

    public function itemAccessPlan(): ItemAccessPlan|null
    {
        return $this->itemAccessPlan;
    }

    public function price(): Price|null
    {
        return $this->price;
    }

    public function availableFrom(): CarbonImmutable|null
    {
        return $this->availableFrom;
    }

    public function availableTo(): CarbonImmutable|null
    {
        return $this->availableTo;
    }

    public function isAvailable(): bool
    {
        $now = CarbonImmutable::now();

        if (is_null($this->availableTo)) {
            return $this->availableFrom <= $now;
        }

        return $this->availableFrom <= $now && $now <= $this->availableTo;
    }

    // modifiers

    public function changeItemAccessPlan(ItemAccessPlan|null $itemAccessPlan): void
    {
        $this->recordApplyAndPublishThat(new AccessPlanChanged($itemAccessPlan));
    }

    public function changePrice(Price|null $price): void
    {
        $this->recordApplyAndPublishThat(new PriceChanged($price));
    }

    public function changeAvailableFrom(CarbonImmutable|null $availableFrom): void
    {
        $this->recordApplyAndPublishThat(new AvailableFromChanged($availableFrom));
    }

    public function changeAvailableTo(CarbonImmutable|null $availableTo): void
    {
        $this->recordApplyAndPublishThat(new AvailableToChanged($availableTo));
    }

    // appliers

    protected function applyPricePlanCreated(PricePlanCreated $event): void
    {
    }

    protected function applyAccessPlanChanged(AccessPlanChanged $event): void
    {
        $this->itemAccessPlan = $event->accessPlan();
    }

    protected function applyPriceChanged(PriceChanged $event): void
    {
        $this->price = $event->price();
    }

    protected function applyAvailableFromChanged(AvailableFromChanged $event): void
    {
        $this->availableFrom = $event->availableFrom();
    }

    protected function applyAvailableToChanged(AvailableToChanged $event): void
    {
        $this->availableTo = $event->availableTo();
    }
}
