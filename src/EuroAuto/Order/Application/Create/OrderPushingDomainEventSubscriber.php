<?php

namespace ApiFacade\EuroAuto\Order\Application\Create;

use ApiFacade\EuroAuto\Order\Domain\OrderPushingDomainEvent;
use ApiFacade\Shared\Domain\Bus\Event\DomainEventSubscriber;

class OrderPushingDomainEventSubscriber implements DomainEventSubscriber
{

    public static function subscribedTo(): array
    {
        return [
            OrderPushingDomainEvent::class
        ];
    }
}
