<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Price\ValueObject;

use Konair\HAP\Payment\Domain\Model\Price\Exception\InvalidIsoCodeException;
use PHPUnit\Framework\TestCase;

final class CurrencyTest extends TestCase
{
    public function testCurrenciesIsEquals(): void
    {
        $currency = new Currency('HUF');
        $newCurrency = new Currency('HUF');

        $this->assertTrue($currency->equalsTo($newCurrency));
    }

    public function testCurrenciesIsNotEquals(): void
    {
        $currency = new Currency('HUF');
        $newCurrency = new Currency('EUR');

        $this->assertFalse($currency->equalsTo($newCurrency));
    }

    public function testTooLongIsoCode(): void
    {
        $this->expectException(InvalidIsoCodeException::class);

        new Currency('HUFF');
    }

    public function testTooShortIsoCode(): void
    {
        $this->expectException(InvalidIsoCodeException::class);

        new Currency('HU');
    }

    public function testInvalidCharacterInIsoCode(): void
    {
        $this->expectException(InvalidIsoCodeException::class);

        new Currency('HU3');
    }

    public function testCopiedCurrencyShouldRepresentSameValue(): void
    {
        $currency = new Currency('HUF');
        $copiedCurrency = Currency::fromCurrency($currency);

        $this->assertTrue($currency->equalsTo($copiedCurrency));
    }

    public function testCurrencyToCastToString(): void
    {
        $currency = new Currency('HUF');

        $this->assertIsString((string)$currency);
    }
}
