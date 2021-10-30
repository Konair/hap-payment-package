<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Price;

interface Vat
{
    public function name(): string;

    public function description(): string;

    /**
     * @return int three-digit numeric (ISO 3166-1 numeric) code
     */
    public function countryCode(): int;

    public function vatValue(): float;
}
