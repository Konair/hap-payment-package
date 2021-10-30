<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Domain\Model\Cart;

use Konair\HAP\Payment\Domain\Model\Cart\Cart;
use Konair\HAP\Payment\Domain\Model\Cart\CartRepository;
use Konair\HAP\Payment\Domain\Model\Cart\Event\CartCreated;
use Konair\HAP\Payment\Domain\Model\Cart\Exception\CartDoesNotExistsException;
use Konair\HAP\Payment\Domain\Model\Cart\ValueObject\CartId;
use Konair\HAP\Payment\Domain\Service\Cart\Specification;
use Konair\HAP\Shared\Domain\Model\EventStore\EventStore;

final class EventStoreCartRepository implements CartRepository
{
    private EventStore $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function byId(CartId $cartId): Cart
    {
        $eventStream = $this->eventStore->allStoredEvent($cartId);

        if (count($eventStream->events()) === 0) {
            throw new CartDoesNotExistsException();
        }

        return Cart::reconstitute($eventStream);
    }

    /**
     * @return Cart[]
     */
    public function all(): array
    {
        $events = $this->eventStore->byType(CartCreated::class);

        return array_map(function (CartCreated $event) {
            return $this->byId($event->cartId());
        }, [...$events]);
    }

    /**
     * @param Specification[] $specifications
     * @return Cart[]
     */
    public function query(array $specifications): array
    {
        return []; // todo
    }
}
