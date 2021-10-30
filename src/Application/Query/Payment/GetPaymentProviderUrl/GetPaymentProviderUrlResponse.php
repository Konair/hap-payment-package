<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Query\Payment\GetPaymentProviderUrl;

use Konair\HAP\Shared\Application\Contract\Response;

final class GetPaymentProviderUrlResponse implements Response
{
    public function __construct(
        private string $redirectUrl,
    ) {
    }

    public function redirectUrl(): string
    {
        return $this->redirectUrl;
    }
}
