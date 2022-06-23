<?php

declare(strict_types=1);

namespace ApiFacade\Shared\Domain\Bus\Event;


use ApiFacade\Shared\Domain\Utils;
use DateTimeImmutable;
use Exception;
use Webpatser\Uuid\Uuid;

abstract class DomainEvent
{
    private string $eventId;
    private string $occurredOn;

    /**
     * @throws Exception
     */
    public function __construct(private string $aggregateId, ?string $eventId = null, string $occurredOn = null)
    {
        $this->eventId = $eventId ?: Uuid::generate()->string;
        $this->occurredOn = $occurredOn ?: Utils::dateToString(new DateTimeImmutable());
    }

    abstract public static function fromPrimitives(
        string $aggregateId,
        array $body,
        string $eventId,
        string $occurredOn
    ): self;

    abstract public static function eventName(): string;

    abstract public function toPrimitives(): array;

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function occurredOn(): string
    {
        return $this->occurredOn;
    }
}
