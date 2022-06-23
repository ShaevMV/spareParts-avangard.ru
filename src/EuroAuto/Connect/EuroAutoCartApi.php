<?php

namespace ApiFacade\EuroAuto\Connect;

use ApiFacade\EuroAuto\Cart\Exception\CartException;
use ApiFacade\EuroAuto\Helpers\EuroAutoHelper;
use ApiFacade\EuroAuto\Responses\Carts\CartInterface;
use ApiFacade\EuroAuto\Responses\Carts\GetCart\CartDto;
use ApiFacade\EuroAuto\Responses\Carts\GetCart\CartInfoDto as GetCartInfoDto;
use ApiFacade\EuroAuto\Responses\Carts\NewCart\CartInfoDto;
use ApiFacade\EuroAuto\Responses\Shared\OffersDto;
use ApiFacade\Shared\Infrastructure\ConnectApi\Domain\ExceptionConnectApi;
use ApiFacade\Shared\Infrastructure\ConnectApi\Domain\ExceptionNotFound;
use Nette\Utils\JsonException;

class EuroAutoCartApi extends EuroAutoSubmitAnInquiry
{
    /**
     * Создание заказа в корзине
     *
     * @throws ExceptionConnectApi
     * @throws JsonException
     */
    public function createOrAddCart(
        string $id,
        int $quantity,
        int $price,
        string $become
    ): CartInfoDto
    {
        $url = EuroAutoHelper::getUrl(EuroAutoHelper::ADD_PRODUCT_IN_CART_URL, [
            'offer_id' => $id
        ]);

        $response = $this->connect->asForm()->post($url, [
            'quantity' => $quantity,
            'price' => $price,
            'labels' => [
                EuroAutoHelper::LABEL_USER_ORDER_DALION => $become
            ]
        ]);

        $data = $this->getResultResponse($response);

        return CartInfoDto::fromState($data);
    }


    /**
     * Получить данные в корзине
     *
     * @throws ExceptionConnectApi
     * @throws JsonException
     */
    public function getProductInCart(CartInterface $cartDto): ?GetCartInfoDto
    {
        $url = EuroAutoHelper::getUrl(EuroAutoHelper::GET_ITEMS_IN_CART);

        try {
            $response = $this->connect->get($url, [
                'cart_id' => $cartDto->getId()
            ]);

            $data = $this->getResultResponse($response);
        } catch (ExceptionNotFound $exceptionNotFound) {
            return null;
        }

        return GetCartInfoDto::fromState($data);
    }

    /**
     * Получить список корзин
     *
     * @return CartInterface[]
     * @throws ExceptionConnectApi
     * @throws JsonException
     */
    public function getList(): array
    {
        $url = EuroAutoHelper::getUrl(EuroAutoHelper::GET_CART_LIST);

        $response = $this->connect->get($url);

        $data = $this->getResultResponse($response);
        $result = [];
        foreach ($data['carts'] as $cart) {
            $result[] = CartDto::fromState($cart);
        }

        return $result;
    }

    /**
     * @throws ExceptionConnectApi
     * @throws JsonException
     * @throws CartException
     */
    public function remove(string $cartId): void
    {
        $url = EuroAutoHelper::getUrl(EuroAutoHelper::DELETE_CART, [
            'cart_id' => $cartId
        ]);
        $response = $this->connect->delete($url, [
            'cart_id' => $cartId,
        ]);
        $data = $this->getResultResponse($response);

        if($data['cart']['id'] !== $cartId) {
            throw new CartException("Удаленна не верная корзина {$cartId}");
        }
    }
}
