<?php

namespace ApiFacade\Shared\Infrastructure\ConnectApi\Domain;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class HandlerDto extends AbstractionEntity
{
    public function __construct(
        protected string $Authorization,
        protected string $Accept = 'application/json',
    )
    {
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['Authorization'],
            $data['Accept']
        );
    }
}
