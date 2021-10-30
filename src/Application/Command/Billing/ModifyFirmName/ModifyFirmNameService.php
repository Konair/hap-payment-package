<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Billing\ModifyFirmName;

use Konair\HAP\Payment\Application\Command\Billing\ModifyFirmName\Exception\BillingDataNotFoundException;
use Konair\HAP\Payment\Domain\Model\Billing\BillingDataRepository;
use Konair\HAP\Payment\Domain\Model\Billing\Exception\BillingDataDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\FirmName;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\InvalidRequestException;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Konair\HAP\Shared\Domain\Model\ValueObject\Exception\ValidationException;

/**
 * @implements ApplicationService<ModifyFirmNameRequest>
 */
final class ModifyFirmNameService implements ApplicationService
{
    public function __construct(
        private BillingDataRepository $repository,
    ) {
    }

    /**
     * @throws WrongRequestTypeException
     * @throws InvalidRequestException
     * @throws BillingDataNotFoundException
     */
    public function execute(Request $request): ModifyFirmNameResponse
    {
        if (!$request instanceof ModifyFirmNameRequest) {
            throw new WrongRequestTypeException();
        }

        try {
            $billingDataId = BillingDataId::create($request->billingDataId());
            $firmName = is_string($request->firmName())
                ? FirmName::create($request->firmName())
                : null;
        } catch (ValidationException) {
            throw new InvalidRequestException();
        }

        try {
            $billingData = $this->repository->byId($billingDataId);
        } catch (BillingDataDoesNotExistsException) {
            throw new BillingDataNotFoundException();
        }

        $billingData->changeFirmName($firmName);

        return new ModifyFirmNameResponse(
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
