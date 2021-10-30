<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Cart\ModifyBillingData;

use Konair\HAP\Payment\Application\Command\Cart\ModifyBillingData\Exception\CartNotFoundException;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Payment\Domain\Model\Cart\CartRepository;
use Konair\HAP\Payment\Domain\Model\Cart\Exception\CartDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartId;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\InvalidRequestException;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Konair\HAP\Shared\Domain\Model\ValueObject\Exception\ValidationException;

/**
 * @implements ApplicationService<ModifyBillingDataRequest>
 */
final class ModifyBillingDataService implements ApplicationService
{
    public function __construct(private CartRepository $cartRepository)
    {
    }

    /**
     * @throws CartNotFoundException
     * @throws InvalidRequestException
     * @throws WrongRequestTypeException
     */
    public function execute(Request $request): ModifyBillingDataResponse
    {
        if (!$request instanceof ModifyBillingDataRequest) {
            throw new WrongRequestTypeException();
        }

        try {
            $cartId = CartId::create($request->cartId());
            $billingDataId = is_string($request->billingDataId())
                ? BillingDataId::create($request->billingDataId())
                : null;
        } catch (ValidationException) {
            throw new InvalidRequestException();
        }

        try {
            $cart = $this->cartRepository->byId($cartId);
        } catch (CartDoesNotExistsException) {
            throw new CartNotFoundException();
        }

        $cart->changeBillingDataId($billingDataId);

        return new ModifyBillingDataResponse(
            $cart->identification()->value(),
            $cart->buyerId()?->value(),
            $cart->billingDataId()?->value(),
            $cart->items()->toCartItemResponse(),
        );
    }
}
