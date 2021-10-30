<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Cart\ModifyBillingData;

use Konair\HAP\Shared\Application\Contract\Request;

final class ModifyBillingDataRequest implements Request
{
    public function __construct(
        private string $cartId,
        private string|null $billingDataId,
    ) {
    }

    public function cartId(): string
    {
        return $this->cartId;
    }

    public function billingDataId(): string|null
    {
        return $this->billingDataId;
    }
}
