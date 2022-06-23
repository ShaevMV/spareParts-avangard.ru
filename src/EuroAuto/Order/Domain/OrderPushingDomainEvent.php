<?php

namespace ApiFacade\EuroAuto\Order\Domain;

use ApiFacade\Shared\Domain\Bus\Event\DomainEvent;
use Exception;

final class OrderPushingDomainEvent extends DomainEvent
{
    public function __construct(
        string $id,
        protected Order $order,
        ?string $eventId = null,
        ?string $occurredOn = null
    ) {
        parent::__construct($id, $eventId, $occurredOn);
    }


    /**
     * @throws Exception
     */
    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): DomainEvent {
        return new self(
            $aggregateId,
            Order::fromState($body),
            $eventId,
            $occurredOn
        );
    }

    public static function eventName(): string
    {
        return 'order.pushing';
    }

    public function toPrimitives(): array
    {
        return $this->order->toArray();
    }
}
