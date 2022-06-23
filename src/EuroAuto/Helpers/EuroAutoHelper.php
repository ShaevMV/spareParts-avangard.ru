<?php

namespace ApiFacade\EuroAuto\Helpers;

class EuroAutoHelper extends EuroAutoHelperAbstract
{
    public const CORE_URL = 'https://whls.euroauto.ru/api/';

    /** @var string авторизация */
    public const AUTH_URL = 'auth';

    /** @var string Поиск артикулов производителей */
    public const FIND_BY_ARTICLES_URL = 'products/manufacturers/codes';

    /** @var string Получение товаров в наличии по артикулу производителя */
    public const GET_PRODUCT_URL = 'offers/products/manufacturers/{manufacturer_id}/{type}';

    /** @var string Получить детальную информацию о предложениях */
    public const GET_OFFER_INFO_URL = 'v2/offers';

    /** @var string Добавление товара в корзину */
    public const ADD_PRODUCT_IN_CART_URL = 'carts/offers/{offer_id}';

    /** @var string Заказ товаров из корзины */
    public const CREATE_ORDER_URL = 'orders/carts';

    /** @var string Получить список заказов */
    public const GET_ORDER_LIST_URL = 'orders/items';

    /** @var string Удалить заказ */
    public const DELETE_ORDER_URL = 'orders/items/{item_id}';

    /**
     * Данные для авторизации в сервисе
     */
    public const LOGIN = 'opt_89219870852';
    public const PASSWORD = 'opt_89219870852';

    public const LOGIN_TEST = 'AVTOAT';
    public const PASSWORD_TEST = 'wallam160';

    /** @var string Ключ в Redis для хранения токина */
    public const REDIS_AUTH_KEY = 'EuroAutoAuth';
    /**
     * Тип товара новый или б/у
     */
    public const NEW_PRODUCT = 'new';
    public const USED_PRODUCT = 'used';

    /** @var string Метка в корзине для соотношения записи заказа */
    public const LABEL_USER_ORDER_DALION = 'user_order';

    /** @var string Посмотреть товары в корзине */
    public const GET_ITEMS_IN_CART = 'carts/items';

    /** @var string Получить список корзин */
    public const GET_CART_LIST = 'carts';

    public const DELETE_CART = 'carts/{cart_id}';

    /**
     * Список складов
     */
    public const STORE_ID = [
        1004, // Склад №7 (Ангар) 1-й Верхний пер., д. 12 лит. A,
        2170, // "Склад №8 (Парнас)", "СПб, ул. Верхняя, д. 14",
    ];

    public const DELIVERY_NAME = 'Самовывоз с подразделений';

    public static function getUrl(string $url, array $params = []): string
    {
        return self::CORE_URL.self::replace($url, $params);
    }
}
