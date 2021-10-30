<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Cart;

use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartItemId;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\Quantity;
use Konair\HAP\Payment\Domain\Model\Item\ValueObject\ItemId;
use Konair\HAP\Payment\Domain\Model\Price\PricePlan;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePartOrderNumber;
use Konair\HAP\Shared\Domain\Model\Entity\AggregateRoot;

/** @extends AggregateRoot<CartItemId> */
final class CartItem extends AggregateRoot
{
    private PricePlan|null $pricePlan = null;
    private PricePartOrderNumber|null $pricePartOrderNumber = null;
    private Quantity $quantity;
    private bool|null $isGift = null;

    public static function create(ItemId $itemId): self
    {
        return new self(
            CartItemId::create(),
            $itemId,
        );
    }

    public function __construct(
        private CartItemId $identification,
        private ItemId $itemId,
    ) {
        $this->quantity = Quantity::create(1.0);
    }

    // getters

    public function identification(): CartItemId
    {
        return $this->identification;
    }

    public function itemId(): ItemId
    {
        return $this->itemId;
    }

    public function pricePlan(): PricePlan|null
    {
        return $this->pricePlan;
    }

    public function pricePartOrderNumber(): PricePartOrderNumber|null
    {
        return $this->pricePartOrderNumber;
    }

    public function quantity(): Quantity
    {
        return $this->quantity;
    }

    public function isGift(): bool|null
    {
        return $this->isGift;
    }

    // modifiers

    // todo create modifiers

    // appliers

    // todo create appliers
}
