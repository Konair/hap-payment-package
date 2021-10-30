<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Billing\ModifyPhoneNumber;

use Konair\HAP\Shared\Application\Contract\Request;

final class ModifyPhoneNumberRequest implements Request
{
    public function __construct(
        private string $billingDataId,
        private string|null $phoneNumber,
    ) {
    }

    public function billingDataId(): string
    {
        return $this->billingDataId;
    }

    public function phoneNumber(): string|null
    {
        return $this->phoneNumber;
    }
}
