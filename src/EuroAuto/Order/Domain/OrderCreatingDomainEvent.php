<?php

declare(strict_types=1);

namespace ApiFacade\EuroAuto\Order\Domain;

use ApiFacade\EuroAuto\Cart\Domain\Cart;
use ApiFacade\Shared\Domain\Bus\Event\DomainEvent;
use Exception;

class OrderCreatingDomainEvent extends DomainEvent
{
    public function __construct(
        string $id,
        private string $become,
        private Cart $cart,
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
            Cart::fromState($body['cart']),
            $eventId,
            $occurredOn
        );
    }

    public static function eventName(): string
    {
        return 'order.creating';
    }

    public function toPrimitives(): array
    {
        return [
            'become' => $this->become,
            'cart' => $this->cart->toArray(),
        ];
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function getBecome(): string
    {
        return $this->become;
    }
}
