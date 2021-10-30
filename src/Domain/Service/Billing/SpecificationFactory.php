<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Service\Billing;

use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\UserId;

interface SpecificationFactory
{
    public function lastOfUser(UserId $userId): Specification;
}
