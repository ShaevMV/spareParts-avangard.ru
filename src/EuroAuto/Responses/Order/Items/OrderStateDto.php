<?php

namespace ApiFacade\EuroAuto\Responses\Order\Items;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class OrderStateDto extends AbstractionEntity
{
    public function __construct(
        protected string $name,
        protected array $attributes,
    ) {
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['name'],
            $data['attributes']
        );
    }

    public function getName(): string
    {
        return $this->name;
    }
}
