<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Query\Price;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterval;
use Konair\HAP\Payment\Domain\Model\ItemAccessPlan\LengthItemAccessPlan;
use Konair\HAP\Payment\Domain\Model\ItemAccessPlan\PeriodicItemAccessPlan;
use Konair\HAP\Payment\Domain\Model\Price\PricePlan;
use Konair\HAP\Shared\Application\Contract\Response;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
abstract class PricePlanResponse implements Response
{
    public static function createFromPricePlan(PricePlan $pricePlan): static
    {
        $accessPlan = $pricePlan->itemAccessPlan();

        return new static(
            $pricePlan->identification()->value(),
            $accessPlan?->name(),
            $accessPlan instanceof LengthItemAccessPlan
                ? $accessPlan->accessInterval()
                : null,
            $accessPlan instanceof PeriodicItemAccessPlan
                ? $accessPlan->periodStartedAt()
                : null,
            $accessPlan instanceof PeriodicItemAccessPlan
                ? $accessPlan->periodFinishedAt()
                : null,
            $pricePlan->price()?->grossAmount()->amount(),
            $pricePlan->price()?->grossAmount()->currency()->isoCode(),
            $pricePlan->availableFrom(),
            $pricePlan->availableTo(),
        );
    }

    /**
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    final public function __construct(
        private string $pricePlanId,
        private string|null $accessPlan,
        private CarbonInterval|null $lengthAccessPlanInterval,
        private CarbonImmutable|null $periodicItemAccessPlanStartedAt,
        private CarbonImmutable|null $periodicItemAccessPlanFinishedAt,
        private int|null $priceGrossAmount,
        private string|null $priceCurrencyIsoCode,
        private CarbonImmutable|null $availableFrom,
        private CarbonImmutable|null $availableTo,
    ) {
    }

    public function pricePlanId(): string
    {
        return $this->pricePlanId;
    }

    public function accessPlan(): string|null
    {
        return $this->accessPlan;
    }

    public function lengthAccessPlanInterval(): CarbonInterval|null
    {
        return $this->lengthAccessPlanInterval;
    }

    public function periodicItemAccessPlanStartedAt(): CarbonImmutable|null
    {
        return $this->periodicItemAccessPlanStartedAt;
    }

    public function periodicItemAccessPlanFinishedAt(): CarbonImmutable|null
    {
        return $this->periodicItemAccessPlanFinishedAt;
    }

    public function priceGrossAmount(): int|null
    {
        return $this->priceGrossAmount;
    }

    public function priceCurrencyIsoCode(): string|null
    {
        return $this->priceCurrencyIsoCode;
    }

    public function availableFrom(): CarbonImmutable|null
    {
        return $this->availableFrom;
    }

    public function availableTo(): CarbonImmutable|null
    {
        return $this->availableTo;
    }
}
