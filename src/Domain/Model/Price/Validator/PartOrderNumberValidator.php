<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Price\Validator;

use Konair\HAP\Shared\Domain\Model\ValueObject\SymfonyBaseValidator;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class PartOrderNumberValidator extends SymfonyBaseValidator
{
    private const MIN_PART_NUMBER = 1;

    public function validate(mixed $value): void
    {
        $this->violations = $this->validator->validate(
            $value,
            [
                new Type(['type' => 'integer']),
                new GreaterThanOrEqual(self::MIN_PART_NUMBER),
                new NotBlank(),
            ]
        );
    }
}
