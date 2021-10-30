<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Billing\ModifyFirmName;

use Konair\HAP\Shared\Application\Contract\Request;

final class ModifyFirmNameRequest implements Request
{
    public function __construct(
        private string $billingDataId,
        private string|null $firmName,
    ) {
    }

    public function billingDataId(): string
    {
        return $this->billingDataId;
    }

    public function firmName(): string|null
    {
        return $this->firmName;
    }
}
