<?php

namespace ApiFacade\EuroAuto\Cart\Dto;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class ArticularRawDto extends AbstractionEntity
{
    public function __construct(
        public string $articular,
        public int $quantity,
        public string $manufacture,
    ) {
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['articular'],
            $data['quantity'],
            $data['manufacture']
        );
    }
}
