<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Billing\Validator;

use Konair\HAP\Shared\Domain\Model\ValueObject\SymfonyBaseValidator;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class FirmNameValidator extends SymfonyBaseValidator
{
    private const MIN_LENGTH = 1;
    private const MAX_LENGTH = 255;

    public function validate(mixed $value): void
    {
        $this->violations = $this->validator->validate(
            $value,
            [
                new Type(['type' => 'string']),
                new Length(['min' => self::MIN_LENGTH]),
                new Length(['max' => self::MAX_LENGTH]),
                new NotBlank(),
            ]
        );
    }
}
