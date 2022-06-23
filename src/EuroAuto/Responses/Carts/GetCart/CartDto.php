<?php

namespace ApiFacade\EuroAuto\Responses\Carts\GetCart;

use ApiFacade\EuroAuto\Responses\Carts\CartInterface;
use ApiFacade\Shared\Domain\Bus\Query\Response;
use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class CartDto extends AbstractionEntity implements CartInterface, Response
{
    public function __construct(
        protected string $id,
        protected array $labels,
        protected string $created_at,
        protected CartItemsDto $items
    ) {
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['id'],
            $data['labels'],
            $data['created_at'],
            CartItemsDto::fromState((array) $data['items'])
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBecome(): ?string
    {
        return $this->labels['user_order'];
    }
}
