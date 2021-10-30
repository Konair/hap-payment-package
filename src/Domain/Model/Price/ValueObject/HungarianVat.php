<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Price\ValueObject;

use JsonSerializable;
use Konair\HAP\Payment\Domain\Model\Price\Exception\UnknownVatException;
use Konair\HAP\Payment\Domain\Model\Price\Vat;
use Konair\HAP\Shared\Domain\Model\ValueObject\ValueObject;
use Stringable;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
final class HungarianVat implements Vat, ValueObject, Stringable, JsonSerializable
{
    private int $countryCode = 348;
    private const TAM = 10;
    private const AAM = 20;
    private const EU = 30;
    private const EUK = 40;
    private const MAA = 50;

    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public static function TAM(): self
    {
        return new self(self::TAM);
    }

    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public static function AAM(): self
    {
        return new self(self::AAM);
    }

    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public static function EU(): self
    {
        return new self(self::EU);
    }

    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public static function EUK(): self
    {
        return new self(self::EUK);
    }

    /**
     * @SuppressWarnings(PHPMD.ShortMethodName)
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    public static function MAA(): self
    {
        return new self(self::MAA);
    }

    private function __construct(private int $vatId)
    {
    }

    public function name(): string
    {
        return match ($this->vatId) {
            self::TAM => 'TAM',
            self::AAM => 'AAM',
            self::EU => 'EU',
            self::EUK => 'EUK',
            self::MAA => 'MAA',
            default => throw new UnknownVatException(),
        };
    }

    public function description(): string
    {
        return match ($this->vatId) {
            self::TAM => 'tárgyi adómentes',
            self::AAM => 'alanyi adómentes',
            self::EU => 'EU-n belüli értékesítés',
            self::EUK => 'EU-n kívüli értékesítés',
            self::MAA => 'mentes az adó alól',
            default => throw new UnknownVatException(),
        };
    }

    public function countryCode(): int
    {
        return $this->countryCode;
    }

    public function vatValue(): float
    {
        return match ($this->vatId) {
            self::TAM, self::AAM, self::MAA => 0.0,
            self::EU, self::EUK => 27.0,
            default => throw new UnknownVatException(),
        };
    }

    public function equalsTo(ValueObject $valueObject): bool
    {
        return $valueObject instanceof self
            && $valueObject->name() === $this->name()
            && $valueObject->description() === $this->description()
            && $valueObject->vatValue() === $this->vatValue();
    }

    public function jsonSerialize(): string
    {
        return (string)json_encode([
            'name' => $this->name(),
            'value' => $this->vatValue(),
        ]);
    }

    public function __toString(): string
    {
        return $this->jsonSerialize();
    }
}
