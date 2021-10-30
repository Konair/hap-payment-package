<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Price\ValueObject;

use Konair\HAP\Payment\Domain\Model\Price\Exception\InvalidIsoCodeException;
use Konair\HAP\Shared\Domain\Model\ValueObject\ValueObject;
use Stringable;

final class Currency implements ValueObject, Stringable
{
    private string $isoCode;

    /**
     * @param Currency $currency
     * @return self
     * @throws InvalidIsoCodeException
     */
    public static function fromCurrency(Currency $currency): self
    {
        return new self($currency->isoCode());
    }

    /**
     * Currency constructor.
     * @param string $isoCode
     * @throws InvalidIsoCodeException
     */
    public function __construct(string $isoCode)
    {
        $this->setIsoCode($isoCode);
    }

    /**
     * @param string $isoCode
     * @throws InvalidIsoCodeException
     */
    private function setIsoCode(string $isoCode): void
    {
        // ISO code validation
        if (!preg_match('/^[A-Z]{3}$/', $isoCode)) {
            throw new InvalidIsoCodeException();
        }

        $this->isoCode = $isoCode;
    }

    public function isoCode(): string
    {
        return $this->isoCode;
    }

    public function equalsTo(ValueObject $valueObject): bool
    {
        return $valueObject instanceof self
            && $valueObject->isoCode() === $this->isoCode();
    }

    public function __toString(): string
    {
        return $this->isoCode();
    }
}
