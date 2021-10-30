<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Cart;

use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Payment\Domain\Model\Buyer\ValueObject\BuyerId;
use Konair\HAP\Payment\Domain\Model\Cart\Event\BillingDataIdChanged;
use Konair\HAP\Payment\Domain\Model\Cart\Event\BuyerIdChanged;
use Konair\HAP\Payment\Domain\Model\Cart\Event\CartCreated;
use Konair\HAP\Payment\Domain\Model\Cart\Event\CartItemAdded;
use Konair\HAP\Payment\Domain\Model\Cart\Event\CartItemRemoved;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartId;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartItemId;
use Konair\HAP\Shared\Domain\Model\Entity\AggregateRoot;
use Konair\HAP\Shared\Domain\Model\Entity\Exception\WrongIdentificationTypeException;
use Konair\HAP\Shared\Domain\Model\EventStore\EventStream;

/**
 * @extends AggregateRoot<CartId>
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class Cart extends AggregateRoot
{
    private BuyerId|null $buyerId = null;
    private BillingDataId|null $billingDataId = null;
    private CartItemCollection $items;

    public static function create(): self
    {
        $cart = new self(CartId::create());
        $cart->recordApplyAndPublishThat(new CartCreated($cart->identification()));

        return $cart;
    }

    public static function reconstitute(EventStream $history): self
    {
        $cartId = $history->aggregateId();

        if (!$cartId instanceof CartId) {
            throw new WrongIdentificationTypeException();
        }

        $cart = new self($cartId);

        foreach ($history->events() as $event) {
            $cart->applyThat($event);
        }

        return $cart;
    }

    public function __construct(private CartId $identification)
    {
        $this->items = new CartItemCollection();
    }

    // getters

    public function identification(): CartId
    {
        return $this->identification;
    }

    public function buyerId(): BuyerId|null
    {
        return $this->buyerId;
    }

    public function billingDataId(): BillingDataId|null
    {
        return $this->billingDataId;
    }

    public function items(): CartItemCollection
    {
        return $this->items;
    }

    // modifiers

    public function changeBuyerId(BuyerId|null $buyerId): void
    {
        $this->recordApplyAndPublishThat(new BuyerIdChanged($this->identification, $buyerId));
    }

    public function changeBillingDataId(BillingDataId|null $billingDataId): void
    {
        $this->recordApplyAndPublishThat(new BillingDataIdChanged($this->identification, $billingDataId));
    }

    public function addCartItem(CartItem $cartItem): void
    {
        $this->recordApplyAndPublishThat(new CartItemAdded($this->identification, $cartItem));
    }

    public function removeCartItem(CartItemId $cartItemId): void
    {
        $this->recordApplyAndPublishThat(new CartItemRemoved($this->identification, $cartItemId));
    }

    // appliers

    protected function applyCartCreated(CartCreated $event): void
    {
    }

    protected function applyBuyerIdChanged(BuyerIdChanged $event): void
    {
        $this->buyerId = $event->buyerId();
    }

    protected function applyBillingDataIdChanged(BillingDataIdChanged $event): void
    {
        $this->billingDataId = $event->buyerBillingDataId();
    }

    protected function applyCartItemAdded(CartItemAdded $event): void
    {
        $this->items = $this->items->append($event->cartItem());
    }

    protected function applyCartItemRemoved(CartItemRemoved $event): void
    {
         $this->items = $this->items->removeById($event->cartItemId());
    }
}
