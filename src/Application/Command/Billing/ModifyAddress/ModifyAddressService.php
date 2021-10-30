<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Application\Command\Billing\ModifyAddress;

use Konair\HAP\Payment\Application\Command\Billing\ModifyAddress\Exception\BillingDataNotFoundException;
use Konair\HAP\Payment\Domain\Model\Billing\BillingDataRepository;
use Konair\HAP\Payment\Domain\Model\Billing\Exception\BillingDataDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Shared\Application\Contract\ApplicationService;
use Konair\HAP\Shared\Application\Contract\Request;
use Konair\HAP\Shared\Application\Exception\InvalidRequestException;
use Konair\HAP\Shared\Application\Exception\WrongRequestTypeException;
use Konair\HAP\Shared\Domain\Model\Address\ValueObject\Address;
use Konair\HAP\Shared\Domain\Model\Address\ValueObject\City;
use Konair\HAP\Shared\Domain\Model\Address\ValueObject\Country;
use Konair\HAP\Shared\Domain\Model\Address\ValueObject\Line;
use Konair\HAP\Shared\Domain\Model\Address\ValueObject\Zip;
use Konair\HAP\Shared\Domain\Model\ValueObject\Exception\ValidationException;

/**
 * @implements ApplicationService<ModifyAddressRequest>
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class ModifyAddressService implements ApplicationService
{
    public function __construct(
        private BillingDataRepository $repository,
    ) {
    }

    /**
     * @throws WrongRequestTypeException
     * @throws BillingDataNotFoundException
     * @throws InvalidRequestException
     */
    public function execute(Request $request): ModifyAddressResponse
    {
        if (!$request instanceof ModifyAddressRequest) {
            throw new WrongRequestTypeException();
        }

        try {
            $billingDataId = BillingDataId::create($request->billingDataId());
            $address = is_null($request->addressCountry())
            && is_null($request->addressZip())
            && is_null($request->addressCity())
            && is_null($request->addressLine())
                ? null
                : Address::create(
                    Country::create($request->addressCountry()),
                    Zip::create($request->addressZip()),
                    City::create($request->addressCity()),
                    Line::create($request->addressLine()),
                );
        } catch (ValidationException $e) {
            throw InvalidRequestException::fromValidationException($e);
        }

        try {
            $billingData = $this->repository->byId($billingDataId);
        } catch (BillingDataDoesNotExistsException) {
            throw new BillingDataNotFoundException();
        }

        $billingData->changeAddress($address);

        return new ModifyAddressResponse(
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
