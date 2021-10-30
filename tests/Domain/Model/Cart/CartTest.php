<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Cart;

use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Payment\Domain\Model\Buyer\ValueObject\BuyerId;
use Konair\HAP\Payment\Domain\Model\Item\ValueObject\ItemId;
use PHPUnit\Framework\TestCase;

final class CartTest extends TestCase
{
    public function testToCreateCart(): void
    {
        // given
        // when
        $cart = Cart::create();

        // then
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertCount(1, $cart->recordedEvents());
        $this->assertNull($cart->buyerId());
        $this->assertNull($cart->billingDataId());
        $this->assertCount(0, $cart->items());
    }

    public function testToChangeBuyerId(): void
    {
        // given
        $cart = Cart::create();
        $buyerId = BuyerId::create('6770d197-988d-4798-b75c-f8cd5b53280d');

        // when
        $cart->changeBuyerId($buyerId);

        // then
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertTrue($cart->buyerId()?->equalsTo($buyerId));
        $this->assertNull($cart->billingDataId());
        $this->assertCount(0, $cart->items());
    }

    public function testToRemoveBuyerId(): void
    {
        // given
        $cart = Cart::create();
        $buyerId = BuyerId::create('6a3dc59b-414d-4d0e-967c-0f28dfe8a584');
        $cart->changeBuyerId($buyerId);

        // when
        $cart->changeBuyerId(null);

        // then
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertNull($cart->buyerId());
        $this->assertNull($cart->billingDataId());
        $this->assertCount(0, $cart->items());
    }

    public function testToChangeBillingDataId(): void
    {
        // given
        $cart = Cart::create();
        $billingDataId = BillingDataId::create('f38f7d96-b199-4194-bae1-10f86d8fdd22');

        // when
        $cart->changeBillingDataId($billingDataId);

        // then
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertNull($cart->buyerId());
        $this->assertTrue($cart->billingDataId()?->equalsTo($billingDataId));
        $this->assertCount(0, $cart->items());
    }

    public function testToRemoveBillingDataId(): void
    {
        // given
        $cart = Cart::create();
        $billingDataId = BillingDataId::create('f38f7d96-b199-4194-bae1-10f86d8fdd22');
        $cart->changeBillingDataId($billingDataId);

        // when
        $cart->changeBillingDataId(null);

        // then
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertNull($cart->buyerId());
        $this->assertNull($cart->billingDataId());
        $this->assertCount(0, $cart->items());
    }

    public function testToAddItemToCart(): void
    {
        // given
        $cart = Cart::create();
        $itemId = 'a2d63455-f170-4ba4-9dbf-c6e7dc192eb4';
        $cartItem = CartItem::create(ItemId::create($itemId));

        // when
        $cart->addCartItem($cartItem);

        // then
        $this->assertCount(1, $cart->items());
        $this->assertTrue(isset($cart->items()[0]));
        $this->assertSame($itemId, $cart->items()[0]->itemId()->value());
    }

    public function testToRemoveItemFromCart(): void
    {
        // given
        $cart = Cart::create();
        $cartItem = CartItem::create(ItemId::create('114b46f3-637c-4b4f-bd2f-f5be8debbcdb'));
        $cart->addCartItem($cartItem);

        // when
        $cart->removeCartItem($cartItem->identification());

        // then
        $this->assertCount(0, $cart->items());
    }
}
