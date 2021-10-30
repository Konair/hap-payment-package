<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Service\Price;

use Konair\HAP\Payment\Domain\Model\Price\ValueObject\Currency;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\HungarianVat;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\Money;
use PHPUnit\Framework\TestCase;

final class VatCalculatorTest extends TestCase
{
    public function testConvertingFromGrossToNet(): void
    {
        $vatCalculator = new VatCalculator();

        $vat = HungarianVat::EU();
        $grossMoney = new Money(2540, new Currency('HUF'));
        $netMoney = new Money(2000, new Currency('HUF'));
        $vatMoney = new Money(540, new Currency('HUF'));

        $this->assertTrue($vatCalculator->toNet($grossMoney, $vat)->equalsTo($netMoney));
        $this->assertTrue($vatCalculator->toGross($netMoney, $vat)->equalsTo($grossMoney));
        $this->assertTrue($vatCalculator->vatOfGross($grossMoney, $vat)->equalsTo($vatMoney));
    }

    public function testConvertingWithZeroVat(): void
    {
        $vatCalculator = new VatCalculator();

        $vat = HungarianVat::TAM();
        $grossMoney = new Money(2000, new Currency('HUF'));
        $netMoney = new Money(2000, new Currency('HUF'));
        $vatMoney = new Money(0, new Currency('HUF'));

        $this->assertTrue($vatCalculator->toNet($grossMoney, $vat)->equalsTo($netMoney));
        $this->assertTrue($vatCalculator->toGross($netMoney, $vat)->equalsTo($grossMoney));
        $this->assertTrue($vatCalculator->vatOfGross($grossMoney, $vat)->equalsTo($vatMoney));
    }
}
