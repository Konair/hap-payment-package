<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Billing\Validator;

use Konair\HAP\Shared\Domain\Model\ValueObject\SymfonyBaseValidator;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class PhoneValidator extends SymfonyBaseValidator
{
    public function validate(mixed $value): void
    {
        $this->violations = $this->validator->validate(
            $value,
            [
                new Type(['type' => 'string']),
                new NotBlank()
            ]
        );
    }
}
