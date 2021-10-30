<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Billing;

use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\FirmName;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\PhoneNumber;
use Konair\HAP\Payment\Domain\Model\Billing\ValueObject\UserId;
use Konair\HAP\Shared\Domain\Model\Address\ValueObject\Address;
use Konair\HAP\Shared\Domain\Model\Address\ValueObject\City;
use Konair\HAP\Shared\Domain\Model\Address\ValueObject\Country;
use Konair\HAP\Shared\Domain\Model\Address\ValueObject\Line;
use Konair\HAP\Shared\Domain\Model\Address\ValueObject\Zip;
use Konair\HAP\Shared\Domain\Model\Name\ValueObject\FirstName;
use Konair\HAP\Shared\Domain\Model\Name\ValueObject\LastName;
use Konair\HAP\Shared\Domain\Model\Name\ValueObject\Name;
use PHPUnit\Framework\TestCase;

final class BillingDataTest extends TestCase
{
    public function testToCreateBillingData(): void
    {
        // given
        // when
        $billingData = BillingData::create();

        // then
        $this->assertInstanceOf(BillingData::class, $billingData);
        $this->assertCount(1, $billingData->recordedEvents());
        $this->assertNull($billingData->userId());
        $this->assertNull($billingData->name());
        $this->assertNull($billingData->firmName());
        $this->assertNull($billingData->address());
        $this->assertNull($billingData->phoneNumber());
    }

    public function testToChangeUserId(): void
    {
        // given
        $userId = 'be706238-17c6-43ff-a39d-3a3be5d2863d';
        $billingData = BillingData::create();

        // when
        $billingData->changeUserId(UserId::create($userId));

        // then
        $this->assertInstanceOf(UserId::class, $billingData->userId());
        $this->assertSame($userId, $billingData->userId()->value());
    }

    public function testToChangeName(): void
    {
        // given
        $name = Name::create(
            null,
            FirstName::create('John'),
            LastName::create('Doe'),
        );
        $billingData = BillingData::create();

        // when
        $billingData->changeName($name);

        // then
        $this->assertInstanceOf(Name::class, $billingData->name());
        $this->assertTrue($billingData->name()->equalsTo($name));
    }

    public function testToChangeFirmName(): void
    {
        // given
        $firmName = FirmName::create('My firm name inc.');
        $billingData = BillingData::create();

        // when
        $billingData->changeFirmName($firmName);

        // then
        $this->assertInstanceOf(FirmName::class, $billingData->firmName());
        $this->assertTrue($billingData->firmName()->equalsTo($firmName));
    }

    public function testToChangeAddress(): void
    {
        // given
        $address = Address::create(
            Country::create('Hungary'),
            Zip::create('1234'),
            City::create('Budapest'),
            Line::create('Something street 404'),
        );
        $billingData = BillingData::create();

        // when
        $billingData->changeAddress($address);

        // then
        $this->assertInstanceOf(Address::class, $billingData->address());
        $this->assertTrue($billingData->address()->equalsTo($address));
    }

    public function testToChangePhoneNumber(): void
    {
        // given
        $phoneNumber = PhoneNumber::create('+36121234567');
        $billingData = BillingData::create();

        // when
        $billingData->changePhoneNumber($phoneNumber);

        // then
        $this->assertInstanceOf(PhoneNumber::class, $billingData->phoneNumber());
        $this->assertTrue($billingData->phoneNumber()->equalsTo($phoneNumber));
    }
}
