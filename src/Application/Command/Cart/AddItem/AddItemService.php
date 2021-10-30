<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Cart\AddItem;

use Konair\HAP\Payment\Application\Command\Cart\AddItem\Exception\CartNotFoundException;
use Konair\HAP\Payment\Domain\Model\Cart\CartItem;
use Konair\HAP\Payment\Domain\Model\Cart\CartRepository;
use Konair\HAP\Payment\Domain\Model\Cart\Exception\CartDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartId;
use Konair\HAP\Payment\Domain\Model\Item\ValueObject\ItemId;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;

/** @implements ApplicationService<AddItemRequest> */
final class AddItemService implements ApplicationService
{
    public function __construct(
        private CartRepository $cartRepository,
    ) {
    }

    /**
     * @throws WrongRequestTypeException
     * @throws CartNotFoundException
     */
    public function execute(Request $request): AddItemResponse
    {
        if (!$request instanceof AddItemRequest) {
            throw new WrongRequestTypeException();
        }

        $itemId = ItemId::create($request->itemId());
        $cartId = CartId::create($request->cartId());
        $cartItem = CartItem::create($itemId);

        try {
            $cart = $this->cartRepository->byId($cartId);
        } catch (CartDoesNotExistsException) {
            throw new CartNotFoundException();
        }

        $cart->addCartItem($cartItem);

        return new AddItemResponse(
            $cart->identification()->value(),
            $cart->buyerId()?->value(),
            $cart->billingDataId()?->value(),
            $cart->items()->toCartItemResponse(),
        );
    }
}
