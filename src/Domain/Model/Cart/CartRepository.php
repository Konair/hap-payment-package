<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Cart;

use Konair\HAP\Payment\Domain\Model\Cart\Exception\CartDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartId;
use Konair\HAP\Payment\Domain\Service\Cart\Specification;

interface CartRepository
{
    /**
     * @param CartId $cartId
     * @return Cart
     * @throws CartDoesNotExistsException
     */
    public function byId(CartId $cartId): Cart;

    /**
     * @param Specification[] $specifications
     * @return Cart[]
     */
    public function query(array $specifications): array;
}
