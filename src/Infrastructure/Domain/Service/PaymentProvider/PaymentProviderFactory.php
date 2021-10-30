<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Domain\Service\PaymentProvider;

use Konair\HAP\Payment\Domain\Service\PaymentProvider\PayRequestBuilder;
use Konair\HAP\Payment\Infrastructure\Domain\Service\PaymentProvider\Exception\NotExistsException;
use Konair\HAP\Payment\Infrastructure\Domain\Service\PaymentProvider\Exception\NotConfiguredException;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

final class PaymentProviderFactory
{
    private LoggerInterface|null $logger;
    public const PAYMENT_PROVIDER_SAFER_PAY_TWINT = 'saferPayTwint';
    public const PAYMENT_PROVIDER_SAFER_PAY = 'saferPay';
    public const PAYMENT_PROVIDER_BARION = 'barion';
    public const PAYMENT_PROVIDER_SIMPLE_PAY = 'simplePay';
    public const PAYMENT_PROVIDER_PAY_PAL = 'payPal';
    public const PAYMENT_PROVIDER_PAYREXX = 'payrexx';

    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @throws NotExistsException
     * @throws NotConfiguredException
     */
    public function createPayRequestBuilder(string $paymentProviderName): PayRequestBuilder
    {
        $payRequestBuilder = match ($paymentProviderName) {
            self::PAYMENT_PROVIDER_SAFER_PAY_TWINT => $this->container->get('saferPayTwint'),
            self::PAYMENT_PROVIDER_SAFER_PAY => $this->container->get('saferPay'),
            self::PAYMENT_PROVIDER_BARION => $this->container->get('barion'),
            self::PAYMENT_PROVIDER_SIMPLE_PAY => $this->container->get('simplePay'),
            self::PAYMENT_PROVIDER_PAY_PAL => $this->container->get('payPal'),
            self::PAYMENT_PROVIDER_PAYREXX => $this->container->get('payrexx'),
            default => throw new NotExistsException(),
        };

        if (!$payRequestBuilder instanceof PayRequestBuilder) {
            $this->logger?->emergency(
                'Payment provider doest not configured exception',
                [
                    'exceptionClassName' => NotConfiguredException::class,
                    'paymentProviderName' => $paymentProviderName,
                ],
            );
            throw new NotConfiguredException();
        }

        return $payRequestBuilder;
    }
}
