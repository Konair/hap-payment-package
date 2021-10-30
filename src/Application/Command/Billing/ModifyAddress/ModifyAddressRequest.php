<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Billing\ModifyAddress;

use Konair\HAP\Shared\Application\Contract\Request;

final class ModifyAddressRequest implements Request
{
    public function __construct(
        private string $billingDataId,
        private string|null $addressCountry,
        private string|null $addressZip,
        private string|null $addressCity,
        private string|null $addressLine,
    ) {
    }

    public function billingDataId(): string
    {
        return $this->billingDataId;
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
}
