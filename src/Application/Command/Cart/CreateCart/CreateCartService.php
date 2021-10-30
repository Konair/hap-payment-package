<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Cart\CreateCart;

use Konair\HAP\Payment\Domain\Model\Cart\Cart;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;

/**
 * @implements ApplicationService<CreateCartRequest>
 */
final class CreateCartService implements ApplicationService
{
    /**
     * @throws WrongRequestTypeException
     */
    public function execute(Request $request): CreateCartResponse
    {
        if (!$request instanceof CreateCartRequest) {
            throw new WrongRequestTypeException();
        }

        $cart = Cart::create();

        return new CreateCartResponse(
            $cart->identification()->value(),
            $cart->buyerId()?->value(),
            $cart->billingDataId()?->value(),
            $cart->items()->toCartItemResponse(),
        );
    }
}
