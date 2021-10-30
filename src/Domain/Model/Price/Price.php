<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Price;

use Konair\HAP\Payment\Domain\Model\Price\ValueObject\Money;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePartOrderNumber;
use Konair\HAP\Payment\Domain\Service\Price\VatCalculator;

final class Price
{
    private PricePartCollection $priceParts;

    public function __construct(
        private Money $grossAmount,
        private Vat $vat,
    ) {
        $this->priceParts = new PricePartCollection();
    }

    // getters

    public function vat(): Vat
    {
        return $this->vat;
    }

    public function vatAmount(): Money
    {
        return (new VatCalculator())->vatOfGross($this->grossAmount, $this->vat);
    }

    public function grossAmount(): Money
    {
        return $this->grossAmount;
    }

    public function netAmount(): Money
    {
        return (new VatCalculator())->toNet($this->grossAmount, $this->vat);
    }

    // modifiers

    public function splitPrice(Money $nextPartGrossAmount): void
    {
        // todo
        new PricePart(
            $nextPartGrossAmount,
            $this->vat,
            PricePartOrderNumber::create(1),
        );
    }
}
