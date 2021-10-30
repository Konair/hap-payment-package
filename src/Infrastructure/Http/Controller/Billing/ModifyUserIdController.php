<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Http\Controller\Billing;

use Konair\HAP\Payment\Application\Command\Billing\ModifyUserId\Exception\BillingDataNotFoundException;
use Konair\HAP\Payment\Application\Command\Billing\ModifyUserId\ModifyUserIdRequest;
use Konair\HAP\Payment\Application\Command\Billing\ModifyUserId\ModifyUserIdService;
use Konair\HAP\Payment\Domain\Model\Billing\BillingDataRepository;
use Konair\HAP\Shared\Application\Exception\InvalidRequestException;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ModifyUserIdController
{
    use BillingDataResponse;

    public function __construct(private BillingDataRepository $repository)
    {
    }

    public function __invoke(string|null $billingDataId, Request $httpRequest): Response
    {
        if (is_null($billingDataId)) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        $request = new ModifyUserIdRequest(
            $billingDataId,
            $httpRequest->get('userId'),
        );
        $service = new ModifyUserIdService($this->repository);

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
        } catch (BillingDataNotFoundException $e) {
            return new JsonResponse([
                'referenceName' => $e->referenceName(),
                'referenceId' => $e->referenceId(),
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->createFromServiceResponse($response);
    }
}
