<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Cart\RemoveItem;

use Exception;
use Konair\HAP\Payment\Domain\Model\Cart\Cart;
use Konair\HAP\Payment\Domain\Model\Cart\CartItem;
use Konair\HAP\Payment\Domain\Model\Cart\CartRepository;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartId;
use Konair\HAP\Payment\Domain\Model\Item\ValueObject\ItemId;
use PHPUnit\Framework\TestCase;

final class RemoveItemServiceTest extends TestCase
{
    /** @throws Exception */
    public function testToRemoveTheCartItem(): void
    {
        // given
        $cartId = '318dc364-cb0b-4d91-9a9d-180f65527198';
        $cartItem = CartItem::create(ItemId::create('88c7e5cb-7997-4e0b-88bb-19394e07552d'));

        $cart = new Cart(CartId::create($cartId));
        $cart->addCartItem($cartItem);

        $cartRepository = $this->getMockBuilder(CartRepository::class)->getMock();
        $cartRepository->method('byId')->willReturn($cart);

        $request = new RemoveItemRequest($cartId, $cartItem->identification()->value());
        $service = new RemoveItemService($cartRepository);

        // when
        $response = $service->execute($request);

        // then
        $this->assertSame($cartId, $response->cartId());
        $this->assertCount(0, $response->cartItems());
    }
}
