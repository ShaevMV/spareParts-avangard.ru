<?php

namespace ApiFacade\EuroAuto\Cart\Domain;

use ApiFacade\EuroAuto\Cart\Dto\ArticularRawDto;
use ApiFacade\Shared\Domain\Bus\Event\DomainEvent;
use Exception;

final class CartCreatingDomainEvent extends DomainEvent
{
    /**
     * @param  string  $id
     * @param  string  $become
     * @param  array  $particulars
     * @param  string|null  $eventId
     * @param  string|null  $occurredOn
     * @throws Exception
     */
    public function __construct(
        string $id,
        private string $become,
        private array $particulars,
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
        ?string $eventId = null,
        ?string $occurredOn = null
    ): self {
        return new self(
            $aggregateId,
            $body['become'],
            $body['particulars'],
            $eventId,
            $occurredOn
        );
    }

    public static function eventName(): string
    {
        return 'cart.creating';
    }

    public function toPrimitives(): array
    {
        return [
            'become' => $this->become,
            'particulars' => $this->particulars,
        ];
    }

    /**
     * @return ArticularRawDto[]
     */
    public function getParticulars(): array
    {
        $result = [];

        foreach ($this->particulars as $particular) {
            $result[] = ArticularRawDto::fromState($particular);
        }

        return $result;
    }

    public function getBecome(): string
    {
        return $this->become;
    }
}
