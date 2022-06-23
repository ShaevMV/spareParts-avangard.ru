<?php

namespace ApiFacade\EuroAuto\Responses\Order;

use ApiFacade\EuroAuto\Responses\Order\Cursor\CursorDto;
use ApiFacade\EuroAuto\Responses\Order\Items\OrderItemDto;
use ApiFacade\EuroAuto\Responses\Product\Shared\ManufactureCodesDto;
use ApiFacade\EuroAuto\Responses\Product\Shared\ProductsDto;
use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class OrderListDto extends AbstractionEntity
{
    /**
     * @param  OrderItemDto[]  $items
     * @param  ProductsDto[]  $products
     * @param  ManufactureCodesDto[]  $manufacturer_codes
     * @param  CursorDto  $cursor
     */
    public function __construct(
        protected array $items,
        protected array $products,
        protected array $manufacturer_codes,
        protected CursorDto $cursor,
    ) {
    }


    public static function fromState(array $data): self
    {
        $items = [];
        foreach ($data['items'] as $item) {
            $items[] = OrderItemDto::fromState($item);
        }

        $products = [];
        foreach ($data['products'] as $key => $product) {
            $products[$key] = ProductsDto::fromState($product);
        }

        $manufacturerCodes = [];
        foreach ($data['manufacturer_codes'] as $key => $manufacturer_code) {
            $manufacturerCodes[$key] = ManufactureCodesDto::fromState($manufacturer_code);
        }

        return new self(
            $items,
            $products,
            $manufacturerCodes,
            CursorDto::fromState($data['cursor'])
        );
    }

    /**
     * @return OrderItemDto[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return ProductsDto[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }
}
