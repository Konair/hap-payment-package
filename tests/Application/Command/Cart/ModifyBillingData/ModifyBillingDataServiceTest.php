<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Cart\ModifyBillingData;

use Exception;
use Konair\HAP\Payment\Domain\Model\Cart\Cart;
use Konair\HAP\Payment\Domain\Model\Cart\CartRepository;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartId;
use PHPUnit\Framework\TestCase;

final class ModifyBillingDataServiceTest extends TestCase
{
    /** @throws Exception */
    public function testToChangeTheBillingDataId(): void
    {
        // given
        $cartId = '9372d547-524d-44f5-8c25-38e8c406f145';
        $billingDataId = '9929e88f-db01-4176-a5ba-9a114133a9a3';

        $cart = new Cart(CartId::create($cartId));

        $cartRepository = $this->getMockBuilder(CartRepository::class)->getMock();
        $cartRepository->method('byId')->willReturn($cart);

        $request = new ModifyBillingDataRequest($cartId, $billingDataId);
        $service = new ModifyBillingDataService($cartRepository);

        // when
        $response = $service->execute($request);

        // then
        $this->assertSame($cartId, $response->cartId());
        $this->assertSame($billingDataId, $response->billingDataId());
    }

    /** @throws Exception */
    public function testToRemoveTheBillingDataId(): void
    {
        // given
        $cartId = 'ca5f6617-1703-47b4-8f1f-811e8852ef32';

        $cart = new Cart(CartId::create($cartId));

        $cartRepository = $this->getMockBuilder(CartRepository::class)->getMock();
        $cartRepository->method('byId')->willReturn($cart);

        $request = new ModifyBillingDataRequest($cartId, null);
        $service = new ModifyBillingDataService($cartRepository);

        // when
        $response = $service->execute($request);

        // then
        $this->assertSame($cartId, $response->cartId());
        $this->assertNull($response->billingDataId());
    }
}
