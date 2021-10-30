<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Query\Payment\GetPaymentProviderUrl;

use Konair\HAP\Shared\Application\Contract\Request;

final class GetPaymentProviderUrlRequest implements Request
{
    public function __construct(
        private string $paymentProvider,
        private string $cartId,
        private string|null $language,
        private string $commonUrl,
        private string|null $successUrl,
        private string|null $failUrl,
        private string|null $cancelUrl,
        private string|null $timeoutUrl,
    ) {
    }

    public function paymentProvider(): string
    {
        return $this->paymentProvider;
    }

    public function cartId(): string
    {
        return $this->cartId;
    }

    public function language(): string|null
    {
        return $this->language;
    }
    public function commonUrl(): string
    {
        return $this->commonUrl;
    }

    public function successUrl(): string|null
    {
        return $this->successUrl;
    }

    public function failUrl(): string|null
    {
        return $this->failUrl;
    }

    public function cancelUrl(): string|null
    {
        return $this->cancelUrl;
    }

    public function timeoutUrl(): string|null
    {
        return $this->timeoutUrl;
    }
}
