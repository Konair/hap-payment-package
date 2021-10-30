<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Service\User;

interface UserExistsService
{
    public function isExists(string $userId): bool;
}
