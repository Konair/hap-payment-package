<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Domain\Model\ItemAccessPlan;

use Konair\HAP\Payment\Domain\Model\Item\ValueObject\ItemId;

interface ItemAccessPlanRepository
{
    public function getAccessPlanForItem(ItemId $itemId): ItemAccessPlan;
}
