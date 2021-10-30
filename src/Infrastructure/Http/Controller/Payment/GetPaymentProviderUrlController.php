<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Http\Controller\Payment;

use Konair\HAP\Payment\Application\Query\Payment\GetPaymentProviderUrl\Exception\CannotGetRedirectUrlException;
use Konair\HAP\Payment\Application\Query\Payment\GetPaymentProviderUrl\Exception\CartHasNoItemException;
use Konair\HAP\Payment\Application\Query\Payment\GetPaymentProviderUrl\Exception\CartHasNotBillingDataException;
use Konair\HAP\Payment\Application\Query\Payment\GetPaymentProviderUrl\Exception\CartNotFoundException;
use Konair\HAP\Payment\Application\Query\Payment\GetPaymentProviderUrl\Exception\InvalidUrlException;
use Konair\HAP\Payment\Application\Query\Payment\GetPaymentProviderUrl\Exception\LanguageNotAvailableException;
use Konair\HAP\Payment\Application\Query\Payment\GetPaymentProviderUrl\GetPaymentProviderUrlRequest;
use Konair\HAP\Payment\Application\Query\Payment\GetPaymentProviderUrl\GetPaymentProviderUrlService;
use Konair\HAP\Payment\Domain\Model\Cart\CartRepository;
use Konair\HAP\Payment\Infrastructure\Domain\Service\PaymentProvider\PaymentProviderFactory;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class GetPaymentProviderUrlController
{
    public function __construct(
        private CartRepository $cartRepository,
        private PaymentProviderFactory $factory,
    ) {
    }

    public function __invoke(
        string $paymentProvider,
        string $cartId,
        Request $httpRequest,
    ): Response {
        $commonUrl = $httpRequest->get('urls')['common'] ?? null;
        $successUrl = $httpRequest->get('urls')['success'] ?? null;
        $failUrl = $httpRequest->get('urls')['fail'] ?? null;
        $cancelUrl = $httpRequest->get('urls')['cancel'] ?? null;
        $timeoutUrl = $httpRequest->get('urls')['timeout'] ?? null;

        if (is_null($commonUrl)) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        $request = new GetPaymentProviderUrlRequest(
            $paymentProvider,
            $cartId,
            $httpRequest->get('language'),
            $commonUrl,
            $successUrl,
            $failUrl,
            $cancelUrl,
            $timeoutUrl,
        );
        $service = new GetPaymentProviderUrlService(
            $this->cartRepository,
            $this->factory,
        );

        try {
            $response = $service->execute($request);
        } catch (WrongRequestTypeException | CannotGetRedirectUrlException $e) {
            return new JsonResponse([
                'referenceName' => $e->referenceName(),
                'referenceId' => $e->referenceId(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (LanguageNotAvailableException | InvalidUrlException $e) {
            return new JsonResponse([
                'referenceName' => $e->referenceName(),
                'referenceId' => $e->referenceId(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (CartHasNotBillingDataException | CartHasNoItemException $e) {
            return new JsonResponse([
                'referenceName' => $e->referenceName(),
                'referenceId' => $e->referenceId(),
            ], Response::HTTP_FORBIDDEN);
        } catch (CartNotFoundException $e) {
            return new JsonResponse([
                'referenceName' => $e->referenceName(),
                'referenceId' => $e->referenceId(),
            ], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'redirectUrl' => $response->redirectUrl(),
            'links' => [],
        ], Response::HTTP_OK);
    }
}
