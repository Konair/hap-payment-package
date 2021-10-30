<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Price\ValueObject;

use Konair\HAP\Payment\Domain\Model\Price\Exception\DivideByZeroException;
use Konair\HAP\Payment\Domain\Model\Price\Exception\NotEnoughAmountException;
use Konair\HAP\Payment\Domain\Model\Price\Exception\NotEqualsCurrencyException;
use Konair\HAP\Shared\Domain\Model\ValueObject\ValueObject;

final class Money implements ValueObject
{
    public static function fromMoney(Money $money): self
    {
        return new self($money->amount(), $money->currency());
    }

    public static function ofCurrency(Currency $currency): self
    {
        return new self(0, $currency);
    }

    public function __construct(
        private int $amount,
        private Currency $currency
    ) {
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function currency(): Currency
    {
        return $this->currency;
    }

    public function equalsTo(ValueObject $valueObject): bool
    {
        return $valueObject instanceof self
            && $valueObject->currency()->equalsTo($this->currency())
            && $valueObject->amount() === $this->amount();
    }

    /**
     * @param Money $money
     * @return self
     * @throws NotEqualsCurrencyException
     */
    public function add(Money $money): self
    {
        if (!$money->currency()->equalsTo($this->currency())) {
            throw new NotEqualsCurrencyException();
        }

        return new self(
            $this->amount() + $money->amount(),
            $this->currency()
        );
    }

    /**
     * @param Money $money
     * @return self
     * @throws NotEnoughAmountException
     * @throws NotEqualsCurrencyException
     */
    public function subtract(Money $money): self
    {
        if (!$money->currency()->equalsTo($this->currency())) {
            throw new NotEqualsCurrencyException();
        }

        if ($money->amount() > $this->amount()) {
            throw new NotEnoughAmountException();
        }

        return new self(
            $this->amount() - $money->amount(),
            $this->currency()
        );
    }

    /**
     * @param float $percent
     * @return self
     * @throws DivideByZeroException
     */
    public function divide(float $percent): self
    {
        if ($percent === 0.0) {
            throw new DivideByZeroException();
        }

        return new self((int)round($this->amount / 100 * $percent), $this->currency);
    }
}
