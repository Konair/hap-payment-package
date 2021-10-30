<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Query\Billing\GetLastBillingData;

use Konair\HAP\Payment\Application\Query\Billing\GetLastBillingData\Exception\BillingDataNotFoundException;
use Konair\HAP\Payment\Domain\Model\Billing\BillingDataRepository;
use Konair\HAP\Payment\Domain\Model\Billing\Exception\BillingDataDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\UserId;
use Konair\HAP\Payment\Domain\Service\Billing\SpecificationFactory;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\InvalidRequestException;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Konair\HAP\Shared\Domain\Model\ValueObject\Exception\ValidationException;

/**
 * @implements ApplicationService<GetLastBillingDataRequest>
 */
final class GetLastBillingDataService implements ApplicationService
{
    public function __construct(
        private BillingDataRepository $repository,
        private SpecificationFactory $specificationFactory,
    ) {
    }

    /**
     * @throws WrongRequestTypeException
     * @throws InvalidRequestException
     * @throws BillingDataNotFoundException
     */
    public function execute(Request $request): GetLastBillingDataResponse
    {
        if (!$request instanceof GetLastBillingDataRequest) {
            throw new WrongRequestTypeException();
        }

        try {
            $userId = UserId::create($request->userId());
        } catch (ValidationException) {
            throw new InvalidRequestException();
        }

        try {
            $billingData = $this->repository->query([
                    $this->specificationFactory->lastOfUser($userId),
                ])[0] ?? throw new BillingDataDoesNotExistsException();
        } catch (BillingDataDoesNotExistsException) {
            throw new BillingDataNotFoundException();
        }

        return new GetLastBillingDataResponse(
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
