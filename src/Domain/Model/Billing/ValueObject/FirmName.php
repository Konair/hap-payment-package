<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Billing\ValueObject;

use Konair\HAP\Payment\Domain\Model\Billing\Validator\FirmNameValidator;
use Konair\HAP\Shared\Domain\Model\ValueObject\Exception\ValidationException;
use Konair\HAP\Shared\Domain\Model\ValueObject\Validator;
use Konair\HAP\Shared\Domain\Model\ValueObject\ValueObject;

final class FirmName implements ValueObject
{
    private string $firmName;

    public static function create(string|null $firmName, Validator|null $validator = null): self
    {
        return new self($firmName, $validator ?: new FirmNameValidator());
    }

    public function __construct(string|null $firmName, Validator $validator)
    {
        $validator->validate($firmName);

        if (is_null($firmName) || !$validator->isValid()) {
            throw ValidationException::withMessages($validator->getErrorMessages());
        }

        $this->firmName = $firmName;
    }

    public function value(): string
    {
        return $this->firmName;
    }

    public function equalsTo(ValueObject $valueObject): bool
    {
        return $valueObject instanceof self
            && $valueObject->value() === $this->value();
    }

    public function __toString(): string
    {
        return $this->firmName;
    }
}
