<?php

namespace ApiFacade\EuroAuto\Responses\Shared;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class DeliveryDto extends AbstractionEntity
{
    public function __construct(
        protected string $id,
        protected string $name,
        protected array $offer_ids,
    ) {
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['id'],
            $data['name'],
            $data['offer_ids'] ?? []
        );
    }

    public function getOfferIds(): array
    {
        return $this->offer_ids;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
