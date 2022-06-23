<?php

namespace ApiFacade\EuroAuto\Order\Domain;

use ApiFacade\Shared\Domain\Bus\Event\DomainEvent;
use Exception;

class ErrorOrderDomainEvent extends DomainEvent
{
    public function __construct(
        string $id,
        private string $become,
        private string $massage,
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
            $body['become'],
            $body['massage'],
            $eventId,
            $occurredOn
        );
    }

    public static function eventName(): string
    {
        return 'order.error';
    }

    public function toPrimitives(): array
    {
        return [
            'become' => $this->become,
            'massage' => $this->massage,
        ];
    }
}
