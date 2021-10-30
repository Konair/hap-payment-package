<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Cart\ModifyBuyer;

use Konair\HAP\Shared\Application\Contract\Request;

final class ModifyBuyerRequest implements Request
{
    public function __construct(
        private string $cartId,
        private string|null $buyerId,
    ) {
    }

    public function cartId(): string
    {
        return $this->cartId;
    }

    public function buyerId(): string|null
    {
        return $this->buyerId;
    }
}
