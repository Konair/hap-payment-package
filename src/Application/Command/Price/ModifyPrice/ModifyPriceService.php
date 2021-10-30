<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\ModifyPrice;

use BadMethodCallException;
use Konair\HAP\Payment\Application\Command\Price\ModifyPrice\Exception\PricePlanNotFoundException;
use Konair\HAP\Payment\Application\Command\Price\ModifyPrice\Exception\UnknownCurrencyException;
use Konair\HAP\Payment\Application\Command\Price\ModifyPrice\Exception\UnknownVatException;
use Konair\HAP\Payment\Domain\Model\Price\Exception\InvalidIsoCodeException;
use Konair\HAP\Payment\Domain\Model\Price\Exception\PricePlanDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Price\Price;
use Konair\HAP\Payment\Domain\Model\Price\PricePlanRepository;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\Currency;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\HungarianVat;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\Money;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePlanId;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;

/**
 * @implements ApplicationService<ModifyPriceRequest>
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class ModifyPriceService implements ApplicationService
{
    public function __construct(
        private PricePlanRepository $repository,
    ) {
    }

    /**
     * @throws WrongRequestTypeException
     * @throws PricePlanNotFoundException
     * @throws UnknownVatException
     * @throws UnknownCurrencyException
     */
    public function execute(Request $request): ModifyPriceResponse
    {
        if (!$request instanceof ModifyPriceRequest) {
            throw new WrongRequestTypeException();
        }

        $pricePlanId = PricePlanId::create($request->pricePlanId());

        try {
            $money = new Money(
                $request->priceGrossAmount(),
                new Currency($request->priceCurrencyIsoCode())
            );
        } catch (InvalidIsoCodeException) {
            throw new UnknownCurrencyException();
        }

        try {
            $vat = HungarianVat::{$request->priceVatName()}();
        } catch (BadMethodCallException) {
            throw new UnknownVatException();
        }

        try {
            $pricePlan = $this->repository->byId($pricePlanId);
        } catch (PricePlanDoesNotExistsException) {
            throw new PricePlanNotFoundException();
        }

        $pricePlan->changePrice(new Price($money, $vat));

        return ModifyPriceResponse::createFromPricePlan($pricePlan);
    }
}
