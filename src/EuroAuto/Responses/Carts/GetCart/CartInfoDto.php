<?php

namespace ApiFacade\EuroAuto\Responses\Carts\GetCart;

use ApiFacade\EuroAuto\Responses\Carts\CartInterface;
use ApiFacade\EuroAuto\Responses\Product\Shared\ManufactureCodesDto;
use ApiFacade\EuroAuto\Responses\Product\Shared\ProductsDto;
use ApiFacade\EuroAuto\Responses\Product\Shared\StoresDto;
use ApiFacade\EuroAuto\Responses\Shared\DeliveryDto;
use ApiFacade\EuroAuto\Responses\Shared\OffersDto;
use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class CartInfoDto extends AbstractionEntity implements CartInterface
{
    /**
     * @param  CartDto  $cart
     * @param  OffersDto[]  $items
     * @param  ProductsDto[]  $products
     * @param  ManufactureCodesDto[]  $manufacturer_codes
     * @param  StoresDto[]  $stores
     * @param  DeliveryDto[]  $delivery
     */
    public function __construct(
        protected CartDto $cart,
        protected array $items,
        protected array $products,
        protected array $manufacturer_codes,
        protected array $stores,
        protected array $delivery,
    ) {
    }

    public static function fromState(array $data): self
    {
        $items = [];
        foreach ($data['items'] as $item) {
            $items[] = OffersDto::fromState($item);
        }

        $products = [];
        foreach ($data['products'] as $key => $product) {
            $products[$key] = ProductsDto::fromState($product);
        }

        $manufacturerCodes = [];
        foreach ($data['manufacturer_codes'] as $key => $manufacturer_code) {
            $manufacturerCodes[$key] = ManufactureCodesDto::fromState($manufacturer_code);
        }

        $stores = [];
        foreach ($data['stores'] as $key => $store) {
            $stores[$key] = StoresDto::fromState($store);
        }

        $delivery = [];
        foreach ($data['delivery'] as $key => $datum) {
            $delivery[$key] = DeliveryDto::fromState($datum);
        }


        return new self(
            CartDto::fromState($data['cart']),
            $items,
            $products,
            $manufacturerCodes,
            $stores,
            $delivery
        );
    }

    public function getCart(): CartDto
    {
        return $this->cart;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function getManufacturerCodes(): array
    {
        return $this->manufacturer_codes;
    }

    public function getStores(): array
    {
        return $this->stores;
    }

    public function getDelivery(): array
    {
        return $this->delivery;
    }

    public function getId(): string
    {
        return $this->cart->getId();
    }
}
