<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Service\Price;

use Konair\HAP\Payment\Domain\Model\Price\Exception\NotEnoughAmountException;
use Konair\HAP\Payment\Domain\Model\Price\Exception\NotEqualsCurrencyException;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\Money;
use Konair\HAP\Payment\Domain\Model\Price\Vat;

final class VatCalculator
{
    /**
     * @param Money $netMoney
     * @param Vat $vat
     * @return Money
     * @throws NotEqualsCurrencyException
     */
    public function toGross(Money $netMoney, Vat $vat): Money
    {
        $vatAmount = (int)round($netMoney->amount() * $vat->vatValue() / 100);
        $vatMoney = new Money($vatAmount, $netMoney->currency());

        return $netMoney->add($vatMoney);
    }

    /**
     * @param Money $grossMoney
     * @param Vat $vat
     * @return Money
     */
    public function toNet(Money $grossMoney, Vat $vat): Money
    {
        $netAmount = (int)round($grossMoney->amount() / (1 + $vat->vatValue() / 100));

        return new Money($netAmount, $grossMoney->currency());
    }

    /**
     * @param Money $grossMoney
     * @param Vat $vat
     * @return Money
     * @throws NotEnoughAmountException
     * @throws NotEqualsCurrencyException
     */
    public function vatOfGross(Money $grossMoney, Vat $vat): Money
    {
        return $grossMoney->subtract($this->toNet($grossMoney, $vat));
    }
}
