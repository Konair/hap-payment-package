<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Billing\ModifyName;

use Konair\HAP\Payment\Application\Command\Billing\ModifyName\Exception\BillingDataNotFoundException;
use Konair\HAP\Payment\Domain\Model\Billing\BillingDataRepository;
use Konair\HAP\Payment\Domain\Model\Billing\Exception\BillingDataDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\InvalidRequestException;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Konair\HAP\Shared\Domain\Model\Name\ValueObject\FirstName;
use Konair\HAP\Shared\Domain\Model\Name\ValueObject\LastName;
use Konair\HAP\Shared\Domain\Model\Name\ValueObject\Name;
use Konair\HAP\Shared\Domain\Model\ValueObject\Exception\ValidationException;

/**
 * @implements ApplicationService<ModifyNameRequest>
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class ModifyNameService implements ApplicationService
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
    public function execute(Request $request): ModifyNameResponse
    {
        if (!$request instanceof ModifyNameRequest) {
            throw new WrongRequestTypeException();
        }

        try {
            $billingDataId = BillingDataId::create($request->billingDataId());
            $name = is_string($request->firstName()) && is_string($request->lastName())
                ? Name::create(
                    null,
                    FirstName::create($request->firstName()),
                    LastName::create($request->lastName()),
                )
                : null;
        } catch (ValidationException) {
            throw new InvalidRequestException();
        }

        try {
            $billingData = $this->repository->byId($billingDataId);
        } catch (BillingDataDoesNotExistsException) {
            throw new BillingDataNotFoundException();
        }

        $billingData->changeName($name);

        return new ModifyNameResponse(
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
