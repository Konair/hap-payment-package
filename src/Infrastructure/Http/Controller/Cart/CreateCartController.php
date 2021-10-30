<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Http\Controller\Cart;

use Konair\HAP\Payment\Application\Command\Cart\CreateCart\CreateCartRequest;
use Konair\HAP\Payment\Application\Command\Cart\CreateCart\CreateCartService;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CreateCartController
{
    use CartResponse;

    public function __invoke(): Response
    {
        $request = new CreateCartRequest();
        $service = new CreateCartService();

        try {
            $response = $service->execute($request);
        } catch (WrongRequestTypeException $e) {
            return new JsonResponse([
                'referenceName' => $e->referenceName(),
                'referenceId' => $e->referenceId(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->createFromServiceResponse($response);
    }
}
