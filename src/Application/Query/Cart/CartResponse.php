<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Query\Cart;

use Konair\HAP\Shared\Application\Contract\Response;

abstract class CartResponse implements Response
{
    /**
     * @param CartItemResponse[] $cartItems
     */
    public function __construct(
        private string $cartId,
        private string|null $buyerId,
        private string|null $billingDataId,
        private array $cartItems,
    ) {
    }

    public function cartId(): string
    {
        return $this->cartId;
    }

    public function buyerId(): string|null
    {
        return $this->buyerId;
    }

    public function billingDataId(): string|null
    {
        return $this->billingDataId;
    }

    /**
     * @return CartItemResponse[]
     */
    public function cartItems(): array
    {
        return $this->cartItems;
    }
}
