<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Cart\Validator;

use Konair\HAP\Shared\Domain\Model\ValueObject\SymfonyBaseValidator;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class QuantityValidator extends SymfonyBaseValidator
{
    private const MIN_QUANTITY = 1;

    public function validate(mixed $value): void
    {
        $this->violations = $this->validator->validate(
            $value,
            [
                new Type(['type' => 'float']),
                new GreaterThanOrEqual(['value' => self::MIN_QUANTITY]),
                new NotBlank()
            ]
        );
    }
}
