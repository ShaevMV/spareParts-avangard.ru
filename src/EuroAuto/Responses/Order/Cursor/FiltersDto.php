<?php

namespace ApiFacade\EuroAuto\Responses\Order\Cursor;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class FiltersDto extends AbstractionEntity
{
    public function __construct(
        protected string $date_start,
        protected string $data_end,
    )
    {
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['date_start'],
            $data['date_end']
        );
    }
}
