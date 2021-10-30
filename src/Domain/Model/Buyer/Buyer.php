<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Buyer;

use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Payment\Domain\Model\Buyer\Event\BillingDataIdChanged;
use Konair\HAP\Payment\Domain\Model\Buyer\ValueObject\BuyerId;
use Konair\HAP\Shared\Domain\Model\Entity\AggregateRoot;

/** @extends AggregateRoot<BuyerId> */
final class Buyer extends AggregateRoot
{
    private BillingDataId|null $billingDataId = null;

    public function __construct(private BuyerId $identification)
    {
    }

    // getters

    public function identification(): BuyerId
    {
        return $this->identification;
    }

    public function billingDataId(): BillingDataId|null
    {
        return $this->billingDataId;
    }

    // modifiers

    public function changeBillingDataId(BillingDataId|null $billingDataId): void
    {
        $this->recordApplyAndPublishThat(new BillingDataIdChanged($this->identification, $billingDataId));
    }

    // appliers

    protected function applyBillingDataIdChanged(BillingDataIdChanged $event): void
    {
        $this->billingDataId = $event->billingDataId();
    }
}
