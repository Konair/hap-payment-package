<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Cart\ModifyBuyer;

use Exception;
use Konair\HAP\Payment\Domain\Model\Cart\Cart;
use Konair\HAP\Payment\Domain\Model\Cart\CartRepository;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartId;
use PHPUnit\Framework\TestCase;

final class ModifyBuyerServiceTest extends TestCase
{
    /** @throws Exception */
    public function testToChangeTheBillingDataId(): void
    {
        // given
        $cartId = 'c11db965-3790-40ba-9e83-f452a2c1c4c3';
        $buyerId = '59265496-1dc1-4c16-8db6-71de43419ad8';

        $cart = new Cart(CartId::create($cartId));

        $cartRepository = $this->getMockBuilder(CartRepository::class)->getMock();
        $cartRepository->method('byId')->willReturn($cart);

        $request = new ModifyBuyerRequest($cartId, $buyerId);
        $service = new ModifyBuyerService($cartRepository);

        // when
        $response = $service->execute($request);

        // then
        $this->assertSame($cartId, $response->cartId());
        $this->assertSame($buyerId, $response->buyerId());
    }

    /** @throws Exception */
    public function testToRemoveTheBillingDataId(): void
    {
        // given
        $cartId = '787ebef0-ec96-4253-a0af-944eb7c3275f';

        $cart = new Cart(CartId::create($cartId));

        $cartRepository = $this->getMockBuilder(CartRepository::class)->getMock();
        $cartRepository->method('byId')->willReturn($cart);

        $request = new ModifyBuyerRequest($cartId, null);
        $service = new ModifyBuyerService($cartRepository);

        // when
        $response = $service->execute($request);

        // then
        $this->assertSame($cartId, $response->cartId());
        $this->assertNull($response->buyerId());
    }
}
