<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Service\PaymentProvider;

use Konair\HAP\Payment\Domain\Model\Cart\CartItemCollection;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartId;
use Konair\HAP\Payment\Infrastructure\Domain\Service\PaymentProvider\Exception\PaymentProviderException;
use Konair\HAP\Shared\Domain\Model\EmailAddress\ValueObject\EmailAddress;
use Konair\HAP\Shared\Domain\Model\Language\ValueObject\Language;
use Konair\HAP\Shared\Domain\Model\Name\ValueObject\Name;
use Konair\HAP\Shared\Domain\Model\Url\ValueObject\Url;

interface PayRequestBuilder
{
    public function withLanguage(Language $language): void;

    public function withItems(CartItemCollection $cartItems): void;

    public function withCartId(CartId $cartId): void;

    public function withUser(Name $name, EmailAddress $emailAddress): void;

    public function withUrls(
        Url $commonUrl,
        Url|null $successUrl,
        Url|null $failUrl,
        Url|null $cancelUrl,
        Url|null $timeoutUrl
    ): void;

    /** @throws PaymentProviderException */
    public function build(): Url;
}
