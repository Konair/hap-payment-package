<?php

namespace Konair\HAP\Payment\Domain\Model\Price\ValueObject;

use Konair\HAP\Payment\Domain\Model\Price\Exception\DivideByZeroException;
use Konair\HAP\Payment\Domain\Model\Price\Exception\NotEnoughAmountException;
use Konair\HAP\Payment\Domain\Model\Price\Exception\NotEqualsCurrencyException;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
final class MoneyTest extends TestCase
{
    public function testMoniesIsEquals(): void
    {
        $money = new Money(10000, new Currency('HUF'));
        $newMoney = new Money(10000, new Currency('HUF'));

        $this->assertTrue($money->equalsTo($newMoney));
    }

    public function testMoniesIsNotEquals(): void
    {
        $money = new Money(10000, new Currency('HUF'));
        $newMoney = new Money(12000, new Currency('EUR'));

        $this->assertFalse($money->equalsTo($newMoney));
    }

    public function testMoniesCurrencyIsNotEquals(): void
    {
        $money = new Money(10000, new Currency('HUF'));
        $newMoney = new Money(10000, new Currency('EUR'));

        $this->assertFalse($money->equalsTo($newMoney));
    }

    public function testMoniesAmountIsNotEquals(): void
    {
        $money = new Money(10000, new Currency('HUF'));
        $newMoney = new Money(12000, new Currency('HUF'));

        $this->assertFalse($money->equalsTo($newMoney));
    }

    public function testCopiedMoneyShouldRepresentSameValue(): void
    {
        $money = new Money(10000, new Currency('HUF'));
        $copiedMoney = Money::fromMoney($money);

        $this->assertTrue($money->equalsTo($copiedMoney));
    }

    public function testMoneyOfCurrencyShouldRepresentSameValue(): void
    {
        $money = new Money(0, new Currency('HUF'));
        $newMoney = Money::ofCurrency(new Currency('HUF'));

        $this->assertTrue($money->equalsTo($newMoney));
    }

    public function testOriginalMoneyShouldNotBeModifiedOnAddition(): void
    {
        $money = new Money(10000, new Currency('HUF'));
        $money->add(new Money(2000, new Currency('HUF')));

        $this->assertEquals(10000, $money->amount());
    }

    public function testAddMoneyWithDifferentCurrency(): void
    {
        $this->expectException(NotEqualsCurrencyException::class);

        $money = new Money(10000, new Currency('HUF'));
        $money->add(new Money(2000, new Currency('EUR')));
    }

    public function testMoniesShouldBeAdded(): void
    {
        $money = new Money(10000, new Currency('HUF'));
        $newMoney = $money->add(new Money(2000, new Currency('HUF')));

        $this->assertEquals(12000, $newMoney->amount());
    }

    public function testOriginalMoneyShouldNotBeModifiedOnSubtraction(): void
    {
        $money = new Money(10000, new Currency('HUF'));
        $money->subtract(new Money(2000, new Currency('HUF')));

        $this->assertEquals(10000, $money->amount());
    }


    public function testSubtractMoneyWithDifferentCurrency(): void
    {
        $this->expectException(NotEqualsCurrencyException::class);

        $money = new Money(10000, new Currency('HUF'));
        $money->subtract(new Money(2000, new Currency('EUR')));
    }

    public function testSubtractMoreMoneyThanIsAvailable(): void
    {
        $this->expectException(NotEnoughAmountException::class);

        $money = new Money(10000, new Currency('HUF'));
        $money->subtract(new Money(12000, new Currency('HUF')));
    }

    public function testMoniesShouldBeSubtracted(): void
    {
        $money = new Money(10000, new Currency('HUF'));
        $newMoney = $money->subtract(new Money(2000, new Currency('HUF')));

        $this->assertEquals(8000, $newMoney->amount());
    }

    public function testToDivideWithZero(): void
    {
        $this->expectException(DivideByZeroException::class);

        $money = new Money(10000, new Currency('HUF'));

        $money->divide(0.0);
    }

    public function testToDivide(): void
    {
        // given
        $money = new Money(10000, new Currency('HUF'));

        // when
        $decreasedMoney = $money->divide(50.0);

        //then
        $this->assertSame(5000, $decreasedMoney->amount());
    }
}
