<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Cart\RemoveItem;

use Konair\HAP\Shared\Application\Contract\Request;

final class RemoveItemRequest implements Request
{
    public function __construct(
        private string $cartId,
        private string $cartItemId,
    ) {
    }

    public function cartId(): string
    {
        return $this->cartId;
    }

    public function cartItemId(): string
    {
        return $this->cartItemId;
    }
}
