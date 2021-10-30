<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Billing\ValueObject;

use Konair\HAP\Payment\Domain\Model\Billing\Validator\PhoneValidator;
use Konair\HAP\Shared\Domain\Model\ValueObject\Exception\ValidationException;
use Konair\HAP\Shared\Domain\Model\ValueObject\Validator;
use Konair\HAP\Shared\Domain\Model\ValueObject\ValueObject;

final class PhoneNumber implements ValueObject
{
    private string $phone;

    public static function create(string|null $phone, Validator|null $validator = null): self
    {
        return new self($phone, $validator ?: new PhoneValidator());
    }

    public function __construct(string|null $phone, Validator $validator)
    {
        $validator->validate($phone);

        if (is_null($phone) || !$validator->isValid()) {
            throw ValidationException::withMessages($validator->getErrorMessages());
        }

        $this->phone = $phone;
    }

    public function value(): string
    {
        return $this->phone;
    }

    public function equalsTo(ValueObject $valueObject): bool
    {
        return $valueObject instanceof self
            && $valueObject->value() === $this->value();
    }

    public function __toString(): string
    {
        return $this->phone;
    }
}
