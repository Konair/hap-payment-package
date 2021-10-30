<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Price\ModifyPrice;

use Konair\HAP\Shared\Application\Contract\Request;

final class ModifyPriceRequest implements Request
{
    public function __construct(
        private string $pricePlanId,
        private int $priceGrossAmount,
        private string $priceCurrencyIsoCode,
        private string $priceVatName,
    ) {
    }

    public function pricePlanId(): string
    {
        return $this->pricePlanId;
    }

    public function priceGrossAmount(): int
    {
        return $this->priceGrossAmount;
    }

    public function priceCurrencyIsoCode(): string
    {
        return $this->priceCurrencyIsoCode;
    }

    public function priceVatName(): string
    {
        return $this->priceVatName;
    }
}
