<?php

namespace ApiFacade\EuroAuto\Responses\Order\Cursor;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class CursorDto extends AbstractionEntity
{
    public function __construct(
        protected FiltersDto $filters
    ) {
    }

    public static function fromState(array $data): self
    {
        return new self(
            FiltersDto::fromState($data['filters'])
        );
    }
}
