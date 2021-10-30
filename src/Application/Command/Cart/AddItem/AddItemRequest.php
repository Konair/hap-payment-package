<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Cart\AddItem;

use Konair\HAP\Shared\Application\Contract\Request;

final class AddItemRequest implements Request
{
    public function __construct(
        private string $cartId,
        private string $itemId,
    ) {
    }

    public function cartId(): string
    {
        return $this->cartId;
    }

    public function itemId(): string
    {
        return $this->itemId;
    }
}
