<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Billing\CreateBillingData;

use Konair\HAP\Payment\Domain\Model\Billing\BillingData;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;

/**
 * @implements ApplicationService<CreateBillingDataRequest>
 */
final class CreateBillingDataService implements ApplicationService
{
    /**
     * @throws WrongRequestTypeException
     */
    public function execute(Request $request): CreateBillingDataResponse
    {
        if (!$request instanceof CreateBillingDataRequest) {
            throw new WrongRequestTypeException();
        }

        $billingData = BillingData::create();

        return new CreateBillingDataResponse(
            $billingData->identification()->value(),
            $billingData->userId()?->value(),
            $billingData->name()?->prefix()?->value(),
            $billingData->name()?->firstName()->value(),
            $billingData->name()?->lastName()?->value(),
            $billingData->firmName()?->value(),
            $billingData->address()?->country()->value(),
            $billingData->address()?->zip()->value(),
            $billingData->address()?->city()->value(),
            $billingData->address()?->line()->value(),
            $billingData->phoneNumber()?->value(),
        );
    }
}
