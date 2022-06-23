<?php

namespace ApiFacade\Tests\Shared\Infrastructure\Bus\Event\RabbitMq;

use ApiFacade\EuroAuto\Cart\Domain\CartCreatingDomainEvent;
use ApiFacade\Shared\Domain\Bus\Event\DomainEventSubscriber;
use RuntimeException;

class TestAllWorksOnRabbitMqEventsPublished implements DomainEventSubscriber
{
    public static function subscribedTo(): array
    {
        return [
            CartCreatingDomainEvent::class
        ];
    }

    public function __invoke(CartCreatingDomainEvent $event): bool
    {
        return true;
    }
}
