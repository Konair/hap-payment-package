<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Cart\AddItem;

use Exception;
use Konair\HAP\Payment\Domain\Model\Cart\Cart;
use Konair\HAP\Payment\Domain\Model\Cart\CartRepository;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartId;
use PHPUnit\Framework\TestCase;

final class AddItemServiceTest extends TestCase
{
    /** @throws Exception */
    public function testToAddItemToTheCart(): void
    {
        // given
        $cartId = 'b07825f8-4791-4b5d-89df-fa22ebf4965d';
        $itemId = '7986415f-1dc7-4e8a-84e6-3d5e035b8f79';

        $cart = new Cart(CartId::create($cartId));

        $cartRepository = $this->getMockBuilder(CartRepository::class)->getMock();
        $cartRepository->method('byId')->willReturn($cart);

        $request = new AddItemRequest($cartId, $itemId);
        $service = new AddItemService($cartRepository);

        // when
        $response = $service->execute($request);

        // then
        $this->assertSame($cartId, $response->cartId());
        $this->assertCount(1, $response->cartItems());
        $this->assertTrue(isset($response->cartItems()[0]));
        $this->assertSame($itemId, $response->cartItems()[0]->itemId());
        $this->assertSame(1.0, $response->cartItems()[0]->quantity());
    }
}
