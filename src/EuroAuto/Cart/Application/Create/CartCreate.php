<?php

declare(strict_types=1);

namespace ApiFacade\EuroAuto\Cart\Application\Create;

use ApiFacade\EuroAuto\Cart\Application\FindManufacturer\FindManufacturer;
use ApiFacade\EuroAuto\Cart\Application\FindManufacturer\ManufacturerResponse;
use ApiFacade\EuroAuto\Cart\Application\FindOfferProduct\FindOfferProduct;
use ApiFacade\EuroAuto\Cart\Application\GetCartId\GetCartId;
use ApiFacade\EuroAuto\Cart\Application\InsertProductInCart\InsertProductInCart;
use ApiFacade\EuroAuto\Cart\Domain\Cart;
use ApiFacade\EuroAuto\Cart\Domain\CartCreatingDomainEvent;
use ApiFacade\EuroAuto\Cart\Dto\ArticularRawDto;
use ApiFacade\EuroAuto\Cart\EuroAutoOfferService;
use ApiFacade\EuroAuto\Responses\Carts\GetCart\CartDto;
use ApiFacade\Shared\Order\ExceptionOrder;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Nette\Utils\JsonException;
use Throwable;
use Webmozart\Assert\Assert;

final class CartCreate
{
    public function __construct(
        private FindManufacturer $findManufacturer,
        private FindOfferProduct $findOfferProduct,
        private EuroAutoOfferService $offerService,
        private InsertProductInCart $productInCart,
        private GetCartId $getCartId,
    ) {
    }

    /**
     * @throws ExceptionOrder
     * @throws Throwable
     * @throws JsonException
     */
    public function create(CartCreatingDomainEvent $event): Cart
    {
        $nullProduct = [];
        $nullOffers = [];

        $result = $this->addItemToCart(
            $event->getBecome(),
            $event->getParticulars(),
            $nullProduct,
            $nullOffers
        );

        /** @var CartDto $cartId */
        $cartId = $this->getCartId->getId($event->getBecome());

        Assertion::notNull($cartId, 'Не найдена корзина у заказа '.$event->getBecome());

        return Cart::fromState([
            'id' => $cartId->getId(),
            'become' => $event->getBecome(),
            'offers' => $result,
            'nullProduct' => $nullProduct,
            'nullOffer' => $nullOffers
        ]);
    }

    /**
     * Добавить товар в корзину
     *
     * @param  string  $become
     * @param  ArticularRawDto[]  $particulars
     * @param  array  $nullProduct
     * @param  array  $nullOffers
     * @return array
     * @throws Throwable
     */
    private function addItemToCart(
        string $become,
        array $particulars,
        array &$nullProduct,
        array &$nullOffers
    ): array {
        $nullOffers = [];
        $nullProduct = [];
        $result = [];
        foreach ($particulars as $particular) {
            /** @var ManufacturerResponse $manufacturer */
            $manufacturer = $this->findManufacturer->find($particular);

            try {
                Assertion::notNull($manufacturer);

            } catch (AssertionFailedException $exception) {
                $nullProduct[] = $particular->articular;
                continue;
            }
            Assert::notNull($manufacturer);
            $product = $this->findOfferProduct->find(
                $manufacturer->getManufacturerId(),
                $particular->articular
            );
            if (!$product?->isFind()) {
                $nullProduct[] = $particular->articular;
                continue;
            }
            Assert::notNull($product);
            $offer = $this->offerService->getCorrectOffers(
                $product->getOffers(),
                $particular->quantity
            );

            $result[$particular->articular] = $offer;
            if (null === $offer) {
                $nullOffers[] = $particular->articular;
                continue;
            }

            $result[$particular->articular] = $offer;
            $this->productInCart->add($offer, $become);
        }

        return $result;
    }
}
