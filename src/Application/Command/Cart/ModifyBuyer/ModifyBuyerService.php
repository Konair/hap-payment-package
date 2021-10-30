<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Cart\ModifyBuyer;

use Konair\HAP\Payment\Application\Command\Cart\ModifyBuyer\Exception\CartNotFoundException;
use Konair\HAP\Payment\Domain\Model\Buyer\ValueObject\BuyerId;
use Konair\HAP\Payment\Domain\Model\Cart\CartRepository;
use Konair\HAP\Payment\Domain\Model\Cart\Exception\CartDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartId;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\InvalidRequestException;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Konair\HAP\Shared\Domain\Model\ValueObject\Exception\ValidationException;

/**
 * @implements ApplicationService<ModifyBuyerRequest>
 */
final class ModifyBuyerService implements ApplicationService
{
    public function __construct(private CartRepository $cartRepository)
    {
    }

    /**
     * @throws CartNotFoundException
     * @throws InvalidRequestException
     * @throws WrongRequestTypeException
     */
    public function execute(Request $request): ModifyBuyerResponse
    {
        if (!$request instanceof ModifyBuyerRequest) {
            throw new WrongRequestTypeException();
        }

        try {
            $cartId = CartId::create($request->cartId());
            $buyerId = is_string($request->buyerId())
                ? BuyerId::create($request->buyerId())
                : null;
        } catch (ValidationException) {
            throw new InvalidRequestException();
        }

        try {
            $cart = $this->cartRepository->byId($cartId);
        } catch (CartDoesNotExistsException) {
            throw new CartNotFoundException();
        }

        $cart->changeBuyerId($buyerId);

        return new ModifyBuyerResponse(
            $cart->identification()->value(),
            $cart->buyerId()?->value(),
            $cart->billingDataId()?->value(),
            $cart->items()->toCartItemResponse(),
        );
    }
}
