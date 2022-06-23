<?php

namespace ApiFacade\EuroAuto\Cart\Domain;

use ApiFacade\EuroAuto\Cart\Entity\CartEntity;
use ApiFacade\EuroAuto\Order\Domain\OrderCreatingDomainEvent;
use ApiFacade\EuroAuto\Responses\Shared\OffersDto;
use ApiFacade\Shared\Domain\Aggregate\AggregateRoot;
use Exception;
use Webpatser\Uuid\Uuid;

final class Cart extends AggregateRoot
{
    /**
     * @param  string  $id
     * @param  string  $become
     * @param  OffersDto[]  $offers
     * @param  array  $nullProduct
     * @param  array  $nullOffer
     */
    public function __construct(
        protected string $id,
        protected string $become,
        protected array $offers,
        protected array $nullProduct = [],
        protected array $nullOffer = [],
    ) {
    }

    /**
     * @throws Exception
     */
    public static function fromState(array $data): self
    {
        $result = new self(
            $data['id'],
            $data['become'],
            $data['offers'],
            $data['nullProduct'],
            $data['nullOffer']
        );

        $result->record(new OrderCreatingDomainEvent(
            Uuid::generate()->string,
            $result->getBecome(),
            $result
        ));

        return $result;
    }

    public function getBecome(): string
    {
        return $this->become;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
