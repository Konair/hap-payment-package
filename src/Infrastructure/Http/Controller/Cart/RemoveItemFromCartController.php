<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Http\Controller\Cart;

use Konair\HAP\Payment\Application\Command\Cart\RemoveItem\Exception\CartNotFoundException;
use Konair\HAP\Payment\Application\Command\Cart\RemoveItem\RemoveItemRequest;
use Konair\HAP\Payment\Application\Command\Cart\RemoveItem\RemoveItemService;
use Konair\HAP\Payment\Domain\Model\Cart\CartRepository;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class RemoveItemFromCartController
{
    use CartResponse;

    public function __construct(private CartRepository $cartRepository)
    {
    }

    public function __invoke(string|null $cartId, string|null $cartItemId): Response
    {
        if (is_null($cartId) || is_null($cartItemId)) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        $request = new RemoveItemRequest($cartId, $cartItemId);
        $service = new RemoveItemService($this->cartRepository);

        try {
            $response = $service->execute($request);
        } catch (WrongRequestTypeException $e) {
            return new JsonResponse([
                'referenceName' => $e->referenceName(),
                'referenceId' => $e->referenceId(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (CartNotFoundException $e) {
            return new JsonResponse([
                'referenceName' => $e->referenceName(),
                'referenceId' => $e->referenceId(),
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->createFromServiceResponse($response);
    }
}
