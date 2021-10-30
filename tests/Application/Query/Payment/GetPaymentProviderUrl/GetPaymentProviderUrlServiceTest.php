<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Query\Payment\GetPaymentProviderUrl;

use Exception;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Payment\Domain\Model\Cart\Cart;
use Konair\HAP\Payment\Domain\Model\Cart\CartItem;
use Konair\HAP\Payment\Domain\Model\Cart\CartRepository;
use Konair\HAP\Payment\Domain\Model\Item\ValueObject\ItemId;
use Konair\HAP\Payment\Domain\Service\PaymentProvider\PayRequestBuilder;
use Konair\HAP\Payment\Infrastructure\Domain\Service\PaymentProvider\PaymentProviderFactory;
use Konair\HAP\Shared\Domain\Model\Url\ValueObject\Url;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class GetPaymentProviderUrlServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testToCreatePaymentRequest(): void
    {
        // given
        $redirectUri = 'https://redi.rect';
        $repository = $this->getMockBuilder(CartRepository::class)->getMock();
        $repository->method('byId')->willReturn($this->createCart());

        $payRequestBuilder = $this->getMockBuilder(PayRequestBuilder::class)->getMock();
        $payRequestBuilder->method('build')->willReturn(Url::create($redirectUri));

        $container = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $container->method('get')->willReturn($payRequestBuilder);

        $request = new GetPaymentProviderUrlRequest(
            PaymentProviderFactory::PAYMENT_PROVIDER_PAY_PAL,
            '1e51a509-de45-420d-84a2-15a04764d397',
            GetPaymentProviderUrlService::LANG_HU,
            'https://thiswebsite.doesnotexist/',
            null,
            null,
            null,
            null,
        );
        $service = new GetPaymentProviderUrlService($repository, new PaymentProviderFactory($container));

        // when
        $response = $service->execute($request);

        // then
        $this->assertSame($redirectUri, $response->redirectUrl());
    }

    private function createCart(): Cart
    {
        $cart = Cart::create();
        $cart->changeBillingDataId(BillingDataId::create());
        $cart->addCartItem(CartItem::create(ItemId::create()));

        return $cart;
    }
}
