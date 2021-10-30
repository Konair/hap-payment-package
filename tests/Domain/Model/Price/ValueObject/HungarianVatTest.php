<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Price\ValueObject;

use PHPUnit\Framework\TestCase;

final class HungarianVatTest extends TestCase
{
    public function testTAM(): void
    {
        // when
        $vat = HungarianVat::TAM();

        // then
        $this->assertSame('TAM', $vat->name());
        $this->assertSame('tárgyi adómentes', $vat->description());
        $this->assertSame(0.0, $vat->vatValue());
    }

    public function testAAM(): void
    {
        // when
        $vat = HungarianVat::AAM();

        // then
        $this->assertSame('AAM', $vat->name());
        $this->assertSame('alanyi adómentes', $vat->description());
        $this->assertSame(0.0, $vat->vatValue());
    }

    public function testEU(): void
    {
        // when
        $vat = HungarianVat::EU();

        // then
        $this->assertSame('EU', $vat->name());
        $this->assertSame('EU-n belüli értékesítés', $vat->description());
        $this->assertSame(27.0, $vat->vatValue());
    }

    public function testEUK(): void
    {
        // when
        $vat = HungarianVat::EUK();

        // then
        $this->assertSame('EUK', $vat->name());
        $this->assertSame('EU-n kívüli értékesítés', $vat->description());
        $this->assertSame(27.0, $vat->vatValue());
    }

    public function testMAA(): void
    {
        // when
        $vat = HungarianVat::MAA();

        // then
        $this->assertSame('MAA', $vat->name());
        $this->assertSame('mentes az adó alól', $vat->description());
        $this->assertSame(0.0, $vat->vatValue());
    }

    public function testVatEquality(): void
    {
        // given
        $vat1 = HungarianVat::EU();
        $vat2 = HungarianVat::EU();

        // when
        $isEquals = $vat1->equalsTo($vat2);

        // then
        $this->assertTrue($isEquals);
    }

    public function testVatInequality(): void
    {
        // given
        $vat1 = HungarianVat::EU();
        $vat2 = HungarianVat::EUK();

        // when
        $isEquals = $vat1->equalsTo($vat2);

        // then
        $this->assertFalse($isEquals);
    }

    public function testVatToCastToString(): void
    {
        // given
        $vat = HungarianVat::EU();

        // when
        $vatValue = (string)$vat;

        // then
        $this->assertIsString($vatValue);
    }
}
