<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Billing;

use Konair\HAP\Payment\Domain\Model\Billing\Event\AddressChanged;
use Konair\HAP\Payment\Domain\Model\Billing\Event\BillingDataCreated;
use Konair\HAP\Payment\Domain\Model\Billing\Event\FirmNameChanged;
use Konair\HAP\Payment\Domain\Model\Billing\Event\NameChanged;
use Konair\HAP\Payment\Domain\Model\Billing\Event\PhoneNumberChanged;
use Konair\HAP\Payment\Domain\Model\Billing\Event\UserIdChanged;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\BillingDataId;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\FirmName;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\PhoneNumber;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\UserId;
use Konair\HAP\Shared\Domain\Model\Address\ValueObject\Address;
use Konair\HAP\Shared\Domain\Model\Entity\AggregateRoot;
use Konair\HAP\Shared\Domain\Model\Entity\Exception\WrongIdentificationTypeException;
use Konair\HAP\Shared\Domain\Model\EventStore\EventStream;
use Konair\HAP\Shared\Domain\Model\Name\ValueObject\Name;

/**
 * @extends AggregateRoot<BillingDataId>
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class BillingData extends AggregateRoot
{
    private UserId|null $userId = null;
    private FirmName|null $firmName = null;
    private Name|null $name = null;
    private Address|null $address = null;
    private PhoneNumber|null $phoneNumber = null;

    public static function create(): self
    {
        $billingData = new self(BillingDataId::create());
        $billingData->recordApplyAndPublishThat(new BillingDataCreated($billingData->identification()));

        return $billingData;
    }

    public static function reconstitute(EventStream $history): self
    {
        $billingDataId = $history->aggregateId();

        if (!$billingDataId instanceof BillingDataId) {
            throw new WrongIdentificationTypeException();
        }

        $billingData = new self($billingDataId);

        foreach ($history->events() as $event) {
            $billingData->applyThat($event);
        }

        return $billingData;
    }

    public function __construct(private BillingDataId $identification)
    {
    }

    // getters

    public function identification(): BillingDataId
    {
        return $this->identification;
    }

    public function userId(): UserId|null
    {
        return $this->userId;
    }

    public function name(): Name|null
    {
        return $this->name;
    }

    public function firmName(): FirmName|null
    {
        return $this->firmName;
    }

    public function address(): Address|null
    {
        return $this->address;
    }

    public function phoneNumber(): PhoneNumber|null
    {
        return $this->phoneNumber;
    }

    // modifiers

    public function changeUserId(UserId|null $userId): void
    {
        $this->recordApplyAndPublishThat(new UserIdChanged($this->identification, $userId));
    }

    public function changeName(Name|null $name): void
    {
        $this->recordApplyAndPublishThat(new NameChanged($this->identification, $name));
    }

    public function changeFirmName(FirmName|null $firmName): void
    {
        $this->recordApplyAndPublishThat(new FirmNameChanged($this->identification, $firmName));
    }

    public function changeAddress(Address|null $address): void
    {
        $this->recordApplyAndPublishThat(new AddressChanged($this->identification, $address));
    }

    public function changePhoneNumber(PhoneNumber|null $phoneNumber): void
    {
        $this->recordApplyAndPublishThat(new PhoneNumberChanged($this->identification, $phoneNumber));
    }

    // appliers

    protected function applyBillingDataCreated(BillingDataCreated $event): void
    {
    }

    protected function applyUserIdChanged(UserIdChanged $event): void
    {
        $this->userId = $event->userId();
    }

    protected function applyNameChanged(NameChanged $event): void
    {
        $this->name = $event->name();
    }

    protected function applyFirmNameChanged(FirmNameChanged $event): void
    {
        $this->firmName = $event->firmName();
    }

    protected function applyAddressChanged(AddressChanged $event): void
    {
        $this->address = $event->address();
    }

    protected function applyPhoneNumberChanged(PhoneNumberChanged $event): void
    {
        $this->phoneNumber = $event->phoneNumber();
    }
}
