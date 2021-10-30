<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\Price;

use Konair\HAP\Payment\Domain\Model\Price\Exception\EmptyPricePartCollectionException;
use Konair\HAP\Payment\Domain\Model\Price\Exception\PricePartNotFoundException;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\Money;
use Konair\HAP\Payment\Domain\Model\Price\ValueObject\PricePartOrderNumber;
use Konair\HAP\Shared\Domain\Model\Collection\ArrayCollection\ImmutableArrayCollection;

/**
 * @extends ImmutableArrayCollection<int, PricePartCollection, PricePart>
 * @property PricePart[] $elements
 */
final class PricePartCollection extends ImmutableArrayCollection
{
    public function ofOrderNumber(PricePartOrderNumber $orderNumber): PricePart
    {
        $pricePart = array_filter(
            $this->elements,
            fn($pricePart) => $pricePart->orderNumber()->equalsTo($orderNumber)
        )[0] ?? false;

        return $pricePart ?: throw new PricePartNotFoundException();
    }

    public function totalDiscountedGrossAmount(): Money
    {
        $total = null;

        foreach ($this->elements as $pricePart) {
            $total = is_null($total)
                ? $pricePart->grossAmount()
                : $pricePart->grossAmount()->add($total);
        }

        return $total instanceof Money ? $total : throw new EmptyPricePartCollectionException();
    }

    /**
     * {@inheritDoc}
     * @param PricePart $element
     */
    public function removeElement(mixed $element): self
    {
        return new self(...array_filter(
            $this->elements,
            fn($pricePart) => $pricePart->orderNumber()->equalsTo($element->orderNumber())
        ));
    }
}
