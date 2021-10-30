<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Billing\ModifyUserId;

use Konair\HAP\Payment\Application\Command\Billing\ModifyUserId\Exception\BillingDataNotFoundException;
use Konair\HAP\Payment\Domain\Model\Billing\BillingDataRepository;
use Konair\HAP\Payment\Domain\Model\Billing\Exception\BillingDataDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\UserId;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\InvalidRequestException;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Konair\HAP\Shared\Domain\Model\ValueObject\Exception\ValidationException;

/**
 * @implements ApplicationService<ModifyUserIdRequest>
 */
final class ModifyUserIdService implements ApplicationService
{
    public function __construct(
        private BillingDataRepository $repository,
    ) {
    }

    /**
     * @param Request $request
     * @return ModifyUserIdResponse
     * @throws WrongRequestTypeException
     * @throws BillingDataNotFoundException
     * @throws InvalidRequestException
     */
    public function execute(Request $request): ModifyUserIdResponse
    {
        if (!$request instanceof ModifyUserIdRequest) {
            throw new WrongRequestTypeException();
        }

        try {
            $billingDataId = BillingDataId::create($request->billingDataId());
            $userId = is_string($request->userId())
                ? UserId::create($request->userId())
                : null;
        } catch (ValidationException) {
            throw new InvalidRequestException();
        }

        try {
            $billingData = $this->repository->byId($billingDataId);
        } catch (BillingDataDoesNotExistsException) {
            throw new BillingDataNotFoundException();
        }

        $billingData->changeUserId($userId);

        return new ModifyUserIdResponse(
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
