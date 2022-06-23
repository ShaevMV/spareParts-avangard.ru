<?php

namespace ApiFacade\EuroAuto\Responses\Carts\NewCart;

use ApiFacade\EuroAuto\Responses\Carts\CartInterface;
use ApiFacade\Shared\Domain\Bus\Query\Response;
use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class CartDto extends AbstractionEntity implements Response,CartInterface
{
    public function __construct(
        protected string $id,
        protected array $labels
    ) {
    }

    /**
     * @param  array  $data
     * @return self
     */
    public static function fromState(array $data): self
    {
        return new self(
            $data['id'],
            $data['labels']
        );
    }

    public function getId(): string
    {
        return $this->id;
    }
}
