<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Billing\ModifyName;

use Konair\HAP\Shared\Application\Contract\Request;

final class ModifyNameRequest implements Request
{
    public function __construct(
        private string $billingDataId,
        private string|null $firstName,
        private string|null $lastName,
    ) {
    }

    public function billingDataId(): string
    {
        return $this->billingDataId;
    }

    public function firstName(): string|null
    {
        return $this->firstName;
    }

    public function lastName(): string|null
    {
        return $this->lastName;
    }
}
