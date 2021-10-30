<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Http\Controller\Cart;

use Konair\HAP\Payment\Application\Command\Cart\ModifyBillingData\Exception\CartNotFoundException;
use Konair\HAP\Payment\Application\Command\Cart\ModifyBillingData\ModifyBillingDataRequest;
use Konair\HAP\Payment\Application\Command\Cart\ModifyBillingData\ModifyBillingDataService;
use Konair\HAP\Payment\Domain\Model\Cart\CartRepository;
use Konair\HAP\Shared\Application\Exception\InvalidRequestException;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ModifyBillingDataController
{
    use CartResponse;

    public function __construct(private CartRepository $cartRepository)
    {
    }

    public function __invoke(string|null $cartId, Request $httpRequest): Response
    {
        if (is_null($cartId)) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        $request = new ModifyBillingDataRequest($cartId, $httpRequest->get('billingDataId'));
        $service = new ModifyBillingDataService($this->cartRepository);

        try {
            $response = $service->execute($request);
        } catch (WrongRequestTypeException $e) {
            return new JsonResponse([
                'referenceName' => $e->referenceName(),
                'referenceId' => $e->referenceId(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (InvalidRequestException $e) {
            return new JsonResponse([
                'referenceName' => $e->referenceName(),
                'referenceId' => $e->referenceId(),
                'messages' => $e->messages(),
            ], Response::HTTP_BAD_REQUEST);
        } catch (CartNotFoundException $e) {
            return new JsonResponse([
                'referenceName' => $e->referenceName(),
                'referenceId' => $e->referenceId(),
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->createFromServiceResponse($response);
    }
}
