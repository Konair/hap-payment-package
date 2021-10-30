<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Cart\RemoveItem;

use Konair\HAP\Payment\Application\Command\Cart\RemoveItem\Exception\CartNotFoundException;
use Konair\HAP\Payment\Domain\Model\Cart\CartRepository;
use Konair\HAP\Payment\Domain\Model\Cart\Exception\CartDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartId;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartItemId;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;

/** @implements ApplicationService<RemoveItemRequest> */
final class RemoveItemService implements ApplicationService
{
    public function __construct(
        private CartRepository $cartRepository,
    ) {
    }

    /**
     * @throws WrongRequestTypeException
     * @throws CartNotFoundException
     */
    public function execute(Request $request): RemoveItemResponse
    {
        if (!$request instanceof RemoveItemRequest) {
            throw new WrongRequestTypeException();
        }

        $cartId = CartId::create($request->cartId());
        $cartItemId = CartItemId::create($request->cartItemId());

        try {
            $cart = $this->cartRepository->byId($cartId);
        } catch (CartDoesNotExistsException) {
            throw new CartNotFoundException();
        }

        $cart->removeCartItem($cartItemId);

        return new RemoveItemResponse(
            $cart->identification()->value(),
            $cart->buyerId()?->value(),
            $cart->billingDataId()?->value(),
            $cart->items()->toCartItemResponse(),
        );
    }
}
