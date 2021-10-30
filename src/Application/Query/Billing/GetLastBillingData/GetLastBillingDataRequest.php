<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Query\Billing\GetLastBillingData;

use Konair\HAP\Shared\Application\Contract\Request;

final class GetLastBillingDataRequest implements Request
{
    public function __construct(private string $userId)
    {
    }

    public function userId(): string
    {
        return $this->userId;
    }
}
