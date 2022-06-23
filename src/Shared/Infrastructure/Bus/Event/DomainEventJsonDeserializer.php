<?php

declare(strict_types=1);

namespace ApiFacade\Shared\Infrastructure\Bus\Event;

use ApiFacade\Shared\Domain\Bus\Event\DomainEvent;
use ApiFacade\Shared\Domain\Utils;
use JsonException;
use RuntimeException;

final class DomainEventJsonDeserializer
{
    public function __construct(private DomainEventMapping $mapping)
    {
    }

    /**
     * @throws JsonException
     */
    public function deserialize(string $domainEvent): DomainEvent
    {
        $eventData = Utils::jsonDecode($domainEvent);
        $eventName = $eventData['data']['type'];
        /** @var class-string<DomainEvent>|null $eventClass */
        $eventClass = $this->mapping->for($eventName);

        if (null === $eventClass) {
            throw new RuntimeException("The event <$eventName> doesn't exist or has no subscribers");
        }

        return $eventClass::fromPrimitives(
            $eventData['data']['attributes']['id'],
            $eventData['data']['attributes'],
            $eventData['data']['id'],
            $eventData['data']['occurred_on']
        );
    }
}
