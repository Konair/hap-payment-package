<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Http\Controller\Cart;

use Konair\HAP\Payment\Application\Query\Cart\CartItemResponse;
use Konair\HAP\Payment\Application\Query\Cart\CartResponse as CartApplicationResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait CartResponse
{
    protected function createFromServiceResponse(CartApplicationResponse $response): Response
    {
        return new JsonResponse([
            'cartId' => $response->cartId(),
            'buyerId' => $response->buyerId(),
            'billingDataId' => $response->billingDataId(),
            'cartItems' => array_map(fn(CartItemResponse $cartItem) => [
                'cartItemId' => $cartItem->cartItemId(),
                'itemId' => $cartItem->itemId(),
                'pricePartOrderNumber' => $cartItem->pricePartOrderNumber(),
                'quantity' => $cartItem->quantity(),
                'isGift' => $cartItem->isGift(),
            ], $response->cartItems()),
            'links' => [
                [
                    'href' => '',
                    'rel' => 'addItemToCart',
                    'type' => 'PUT',
                ],
                [
                    'href' => '',
                    'rel' => 'removeItemFromCart',
                    'type' => 'PUT',
                ],
                [
                    'href' => '',
                    'rel' => 'modifyBillingDataId',
                    'type' => 'PUT',
                ],
                [
                    'href' => '',
                    'rel' => 'modifyBuyerId',
                    'type' => 'PUT',
                ],
            ],
        ], Response::HTTP_OK);
    }
}
