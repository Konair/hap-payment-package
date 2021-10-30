<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Query\Cart;

final class CartItemResponse
{
    public function __construct(
        private string $cartItemId,
        private string $itemId,
        //private null $pricePlan,
        private int|null $pricePartOrderNumber,
        private float|null $quantity,
        private bool|null $isGift,
    ) {
    }

    public function cartItemId(): string
    {
        return $this->cartItemId;
    }

    public function itemId(): string
    {
        return $this->itemId;
    }

    public function pricePartOrderNumber(): int|null
    {
        return $this->pricePartOrderNumber;
    }

    public function quantity(): float|null
    {
        return $this->quantity;
    }

    public function isGift(): bool|null
    {
        return $this->isGift;
    }
}
