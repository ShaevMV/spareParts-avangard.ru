<?php

declare(strict_types=1);

namespace ApiFacade\EuroAuto\Responses\Order;

use ApiFacade\EuroAuto\Responses\Product\Shared\ManufactureCodesDto;
use ApiFacade\EuroAuto\Responses\Product\Shared\ProductsDto;
use ApiFacade\EuroAuto\Responses\Product\Shared\StoresDto;
use ApiFacade\EuroAuto\Responses\Shared\ItemDto;
use ApiFacade\EuroAuto\Responses\Shared\OffersDto;
use ApiFacade\Shared\Domain\Bus\Query\Response;
use ApiFacade\Shared\Domain\Entity\AbstractionEntity;
use function Lambdish\Phunctional\first;

class OrderInfoDto extends AbstractionEntity implements Response
{
    /**
     * @param  string  $id
     * @param  ProductsDto[]  $products
     */
    public function __construct(
        protected string $id,
        protected array $products,
    ) {
    }

    public static function fromState(array $data): self
    {
        /** @var string $orderId */
        $orderId = first(array_keys($data['orders']));
        $products = [];
        foreach ($data['products'] as $key => $product) {
            $products[$key] = ProductsDto::fromState($product);
        }

        return new self(
            $orderId,
            $products,
        );
    }


    public function getProducts(): array
    {
        return $this->products;
    }


    public function getId(): string
    {
        return $this->id;
    }
}
