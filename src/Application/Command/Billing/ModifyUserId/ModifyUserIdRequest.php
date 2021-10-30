<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Billing\ModifyUserId;

use Konair\HAP\Shared\Application\Contract\Request;

final class ModifyUserIdRequest implements Request
{
    public function __construct(
        private string $billingDataId,
        private string|null $userId,
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
}
