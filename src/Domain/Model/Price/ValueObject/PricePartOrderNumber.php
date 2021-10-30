<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Price\ValueObject;

use Konair\HAP\Payment\Domain\Model\Price\Validator\PartOrderNumberValidator;
use Konair\HAP\Shared\Domain\Model\ValueObject\Exception\ValidationException;
use Konair\HAP\Shared\Domain\Model\ValueObject\Validator;
use Konair\HAP\Shared\Domain\Model\ValueObject\ValueObject;

final class PricePartOrderNumber implements ValueObject
{
    private int $pricePartOrderNumber;

    public static function create(?int $pricePartOrderNumber, ?Validator $validator = null): self
    {
        return new self($pricePartOrderNumber, $validator ?: new PartOrderNumberValidator());
    }

    public function __construct(?int $pricePartOrderNumber, Validator $validator)
    {
        $validator->validate($pricePartOrderNumber);

        if (!$validator->isValid() || is_null($pricePartOrderNumber)) {
            throw ValidationException::withMessages($validator->getErrorMessages());
        }

        $this->pricePartOrderNumber = $pricePartOrderNumber;
    }

    public function value(): int
    {
        return $this->pricePartOrderNumber;
    }

    public function equalsTo(ValueObject $valueObject): bool
    {
        return $valueObject instanceof self
            && $valueObject->value() === $this->value();
    }

    public function __toString(): string
    {
        return (string)$this->pricePartOrderNumber;
    }
}
