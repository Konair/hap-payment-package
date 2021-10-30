<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Query\Billing;

use Konair\HAP\Shared\Application\Contract\Response;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
abstract class BillingDataResponse implements Response
{
    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        private string $billingDataId,
        private string|null $userId,
        private string|null $namePrefix,
        private string|null $nameFirstName,
        private string|null $nameLastName,
        private string|null $firmName,
        private string|null $addressCountry,
        private string|null $addressZip,
        private string|null $addressCity,
        private string|null $addressLine,
        private string|null $phoneNumber,
    ) {
    }

    public function billingDataId(): string
    {
        return $this->billingDataId;
    }

    public function userId(): string|null
    {
        return $this->userId;
    }

    public function namePrefix(): string|null
    {
        return $this->namePrefix;
    }

    public function nameFirstName(): string|null
    {
        return $this->nameFirstName;
    }

    public function nameLastName(): string|null
    {
        return $this->nameLastName;
    }

    public function firmName(): string|null
    {
        return $this->firmName;
    }

    public function addressCountry(): string|null
    {
        return $this->addressCountry;
    }

    public function addressZip(): string|null
    {
        return $this->addressZip;
    }

    public function addressCity(): string|null
    {
        return $this->addressCity;
    }

    public function addressLine(): string|null
    {
        return $this->addressLine;
    }

    public function phoneNumber(): string|null
    {
        return $this->phoneNumber;
    }
}
