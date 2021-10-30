<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Query\Payment\GetPaymentProviderUrl;

use Konair\HAP\Payment\Application\Query\Payment\GetPaymentProviderUrl\Exception\CannotGetRedirectUrlException;
use Konair\HAP\Payment\Application\Query\Payment\GetPaymentProviderUrl\Exception\CartHasNoItemException;
use Konair\HAP\Payment\Application\Query\Payment\GetPaymentProviderUrl\Exception\CartHasNotBillingDataException;
use Konair\HAP\Payment\Application\Query\Payment\GetPaymentProviderUrl\Exception\CartNotFoundException;
use Konair\HAP\Payment\Application\Query\Payment\GetPaymentProviderUrl\Exception\InvalidUrlException;
use Konair\HAP\Payment\Application\Query\Payment\GetPaymentProviderUrl\Exception\LanguageNotAvailableException;
use Konair\HAP\Payment\Domain\Model\Cart\Cart;
use Konair\HAP\Payment\Domain\Model\Cart\CartRepository;
use Konair\HAP\Payment\Domain\Model\Cart\Exception\CartDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartId;
use Konair\HAP\Payment\Infrastructure\Domain\Service\PaymentProvider\Exception\PaymentProviderException;
use Konair\HAP\Payment\Infrastructure\Domain\Service\PaymentProvider\PaymentProviderFactory;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Konair\HAP\Shared\Domain\Model\EmailAddress\ValueObject\EmailAddress;
use Konair\HAP\Shared\Domain\Model\Language\ValueObject\Language;
use Konair\HAP\Shared\Domain\Model\Name\ValueObject\FirstName;
use Konair\HAP\Shared\Domain\Model\Name\ValueObject\LastName;
use Konair\HAP\Shared\Domain\Model\Name\ValueObject\Name;
use Konair\HAP\Shared\Domain\Model\Url\ValueObject\Url;
use Konair\HAP\Shared\Domain\Model\ValueObject\Exception\ValidationException;

/**
 * @implements ApplicationService<GetPaymentProviderUrlRequest>
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class GetPaymentProviderUrlService implements ApplicationService
{
    public const LANG_HU = 'hu';
    public const LANG_DE = 'de';

    public function __construct(
        private CartRepository $cartRepository,
        private PaymentProviderFactory $factory,
    ) {
    }

    /**
     * @throws CannotGetRedirectUrlException
     * @throws CartHasNoItemException
     * @throws CartHasNotBillingDataException
     * @throws CartNotFoundException
     * @throws InvalidUrlException
     * @throws LanguageNotAvailableException
     * @throws WrongRequestTypeException
     */
    public function execute(Request $request): GetPaymentProviderUrlResponse
    {
        if (!$request instanceof GetPaymentProviderUrlRequest) {
            throw new WrongRequestTypeException();
        }

        try {
            $commonUrl = Url::create($request->commonUrl());
            $successUrl = is_string($request->successUrl()) ? Url::create($request->successUrl()) : null;
            $failUrl = is_string($request->failUrl()) ? Url::create($request->failUrl()) : null;
            $cancelUrl = is_string($request->cancelUrl()) ? Url::create($request->cancelUrl()) : null;
            $timeoutUrl = is_string($request->timeoutUrl()) ? Url::create($request->timeoutUrl()) : null;
        } catch (ValidationException) {
            throw new InvalidUrlException();
        }

        $language = match ($request->language()) {
            self::LANG_HU => Language::HU(),
            self::LANG_DE => Language::DE(),
            default => throw new LanguageNotAvailableException(),
        };

        try {
            $cart = $this->cartRepository->byId(CartId::create($request->cartId()));
        } catch (CartDoesNotExistsException) {
            throw new CartNotFoundException();
        }

        $this->checkTheCartHasAllTheData($cart);

        try {
            $payRequestBuilder = $this->factory->createPayRequestBuilder($request->paymentProvider());
            $payRequestBuilder->withCartId($cart->identification());
            $payRequestBuilder->withLanguage($language);
            $payRequestBuilder->withUrls(
                $commonUrl,
                $successUrl,
                $failUrl,
                $cancelUrl,
                $timeoutUrl,
            );
            $payRequestBuilder->withUser(
                Name::create(null, FirstName::create('John'), LastName::create('Doe')), // todo: get from a service
                EmailAddress::create('john.doe@john.dou') // todo: get from a service
            );
            $payRequestBuilder->withItems($cart->items());
            $redirectUrl = $payRequestBuilder->build();
        } catch (PaymentProviderException) {
            throw new CannotGetRedirectUrlException();
        }

        return new GetPaymentProviderUrlResponse(
            $redirectUrl->value(),
        );
    }

    /**
     * @throws CartHasNoItemException
     * @throws CartHasNotBillingDataException
     */
    private function checkTheCartHasAllTheData(Cart $cart): void
    {
        if (is_null($cart->billingDataId())) {
            throw new CartHasNotBillingDataException();
        }

        if ($cart->items()->count() === 0) {
            throw new CartHasNoItemException();
        }
    }
}
