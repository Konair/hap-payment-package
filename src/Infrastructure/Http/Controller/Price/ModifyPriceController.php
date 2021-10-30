<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Http\Controller\Price;

use Konair\HAP\Payment\Application\Command\Price\ModifyPrice\Exception\PricePlanNotFoundException;
use Konair\HAP\Payment\Application\Command\Price\ModifyPrice\Exception\UnknownCurrencyException;
use Konair\HAP\Payment\Application\Command\Price\ModifyPrice\Exception\UnknownVatException;
use Konair\HAP\Payment\Application\Command\Price\ModifyPrice\ModifyPriceRequest;
use Konair\HAP\Payment\Application\Command\Price\ModifyPrice\ModifyPriceService;
use Konair\HAP\Payment\Domain\Model\Price\PricePlanRepository;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ModifyPriceController
{
    use PricePlanResponse;

    public function __construct(private PricePlanRepository $planRepository)
    {
    }

    public function __invoke(string|null $pricePlanId, Request $httpRequest): Response
    {
        $priceGrossAmount = $httpRequest->get('price')['grossAmount'] ?? null;
        $priceCurrencyIsoCode = $httpRequest->get('price')['currencyIsoCode'] ?? null;
        $priceVatName = $httpRequest->get('price')['vatName'] ?? null;

        if (is_null($pricePlanId)) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        if (!is_int($priceGrossAmount) || is_null($priceCurrencyIsoCode) || is_null($priceVatName)) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        $request = new ModifyPriceRequest(
            $pricePlanId,
            $priceGrossAmount,
            $priceCurrencyIsoCode,
            $priceVatName,
        );
        $service = new ModifyPriceService($this->planRepository);

        try {
            $response = $service->execute($request);
        } catch (WrongRequestTypeException $e) {
            return new JsonResponse([
                'referenceName' => $e->referenceName(),
                'referenceId' => $e->referenceId(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (PricePlanNotFoundException $e) {
            return new JsonResponse([
                'referenceName' => $e->referenceName(),
                'referenceId' => $e->referenceId(),
            ], Response::HTTP_NOT_FOUND);
        } catch (UnknownVatException | UnknownCurrencyException $e) {
            return new JsonResponse([
                'referenceName' => $e->referenceName(),
                'referenceId' => $e->referenceId(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->createFromServiceResponse($response);
    }
}
