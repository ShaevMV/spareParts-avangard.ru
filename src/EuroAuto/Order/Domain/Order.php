<?php

namespace ApiFacade\EuroAuto\Order\Domain;

use ApiFacade\EuroAuto\Cart\Domain\Cart;
use ApiFacade\EuroAuto\Order\Helpers\OrderHelper;
use ApiFacade\EuroAuto\Responses\Order\Items\OrderStateDto;
use ApiFacade\EuroAuto\Responses\Order\OrderInfoDto;
use ApiFacade\EuroAuto\Responses\Product\Shared\ProductsDto;
use ApiFacade\Shared\Domain\Aggregate\AggregateRoot;
use ApiFacade\Shared\Domain\Bus\Query\Response;
use ApiFacade\Shared\Order\ExceptionOrder;
use Exception;

class Order extends AggregateRoot implements Response
{
    /**
     * @param  string|int  $id
     * @param  ProductsDto[]  $products
     * @param  Cart  $cart
     * @param  OrderStateDto|null  $state
     * @throws Exception
     */
    public function __construct(
        protected string|int $id,
        protected array $products,
        protected Cart $cart,
        protected ?OrderStateDto $state = null
    ) {
        if (is_null($this->state)) {
            $this->state = OrderStateDto::fromState([
                'name' => OrderHelper::CREATED,
                'attributes' => [],
            ]);
        }

        $this->record(new OrderPushingDomainEvent(
            (string) $this->id,
            $this
        ));
    }

    /**
     * @throws Exception
     */
    public static function fromState(array $data): self
    {
        $products = [];
        foreach ($data['products'] as $key => $product) {
            $products[$key] = ProductsDto::fromState($product);
        }

        $state = isset($data['state']) ? OrderStateDto::fromState($data['state']) : null;

        return new self(
            $data['id'],
            $products,
            Cart::fromState($data['cart']),
            $state
        );
    }

    /**
     * @throws ExceptionOrder
     * @throws Exception
     */
    public static function fromOrderInfoDto(OrderInfoDto $orderInfoDto, Cart $cartEntity): self
    {
        return new self(
            $orderInfoDto->getId(),
            $orderInfoDto->getProducts(),
            $cartEntity
        );
    }

    public function getId(): string|int
    {
        return $this->id;
    }

    public function getBecome(): string
    {
        return $this->cart->getBecome();
    }

    public function setState(OrderStateDto $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getState(): ?OrderStateDto
    {
        return $this->state;
    }
}
