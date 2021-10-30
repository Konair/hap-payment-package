<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Cart\ValueObject;

use Konair\HAP\Payment\Domain\Model\Cart\Validator\QuantityValidator;
use Konair\HAP\Shared\Domain\Model\ValueObject\Exception\ValidationException;
use Konair\HAP\Shared\Domain\Model\ValueObject\Validator;
use Konair\HAP\Shared\Domain\Model\ValueObject\ValueObject;

final class Quantity implements ValueObject
{
    private float $quantity;

    public static function create(?float $quantity, ?Validator $validator = null): self
    {
        return new self($quantity, $validator ?: new QuantityValidator());
    }

    public function __construct(?float $quantity, Validator $validator)
    {
        $validator->validate($quantity);

        if (!$validator->isValid() || is_null($quantity)) {
            throw ValidationException::withMessages($validator->getErrorMessages());
        }

        $this->quantity = $quantity;
    }

    public function value(): float
    {
        return $this->quantity;
    }

    public function equalsTo(ValueObject $valueObject): bool
    {
        return $valueObject instanceof self
            && $valueObject->value() === $this->value();
    }

    public function __toString(): string
    {
        return (string)$this->quantity;
    }

    public function add(float $quantity): self
    {
        return self::create($this->quantity + $quantity);
    }
}
