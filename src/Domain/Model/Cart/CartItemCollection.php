<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Cart;

use Konair\HAP\Payment\Application\Query\Cart\CartItemResponse;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartItemId;
use Konair\HAP\Shared\Domain\Model\Collection\ArrayCollection\ImmutableArrayCollection;

/**
 * @extends ImmutableArrayCollection<int, CartItemCollection, CartItem>
 */
final class CartItemCollection extends ImmutableArrayCollection
{
    /**
     * {@inheritDoc}
     * @param CartItem $element
     */
    public function removeElement(mixed $element): self
    {
        return new self(...array_filter(
            $this->elements,
            fn($cartItem) => $cartItem->identification()->equalsTo($element->identification())
        ));
    }

    /**
     * @param CartItemId $cartItemId
     * @return CartItemCollection
     */
    public function removeById(CartItemId $cartItemId): self
    {
        return new self(...array_filter(
            $this->elements,
            fn($cartItem) => !$cartItem->identification()->equalsTo($cartItemId)
        ));
    }

    /**
     * @return CartItemResponse[]
     */
    public function toCartItemResponse(): array
    {
        return array_map(fn(CartItem $cartItem) => new CartItemResponse(
            $cartItem->identification()->value(),
            $cartItem->itemId()->value(),
            //$cartItem->pricePlan(),
            $cartItem->pricePartOrderNumber()?->value(),
            $cartItem->quantity()->value(),
            $cartItem->isGift(),
        ), $this->elements);
    }
}
