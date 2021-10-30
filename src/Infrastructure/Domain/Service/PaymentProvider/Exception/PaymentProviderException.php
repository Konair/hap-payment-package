<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Domain\Service\PaymentProvider\Exception;

use Konair\HAP\Shared\Infrastructure\Exception\InfrastructureException;

abstract class PaymentProviderException extends InfrastructureException
{
}
