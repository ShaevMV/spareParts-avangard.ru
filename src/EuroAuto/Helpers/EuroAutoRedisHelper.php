<?php

namespace ApiFacade\EuroAuto\Helpers;

class EuroAutoRedisHelper extends EuroAutoHelperAbstract
{
    /** @var string Ключ в Redis для хранения токина */
    public const REDIS_AUTH_KEY = 'EuroAutoAuth';

    /** @var string Ключ в Redis для хранения корзин всех корзин */
    public const REDIS_ALL_CART = 'EuroAutoCart';

    /** @var string Ключ в Redis для хранения конкретной козины */
    public const REDIS_THIS_CART = 'EuroAutoCart:{become}';

    /** @var string Ключ в Redis для хранения конкретной козины */
    public const REDIS_THIS_CART_ID = 'EuroAutoCartId:{become}';

    /** @var string Ключ в Redis для хранения конкретной козины */
    public const REDIS_THIS_ORDER = 'EuroAutoOrder:{become}';

    public static function getCartKey(string $become): string
    {
        return self::replace(self::REDIS_THIS_CART, [
            'become' => $become,
        ]);
    }

    public static function getCartIdKey(string $become): string
    {
        return self::replace(self::REDIS_THIS_CART_ID, [
            'become' => $become,
        ]);
    }

    public static function getOrderKey(string $become): string
    {
        return self::replace(self::REDIS_THIS_ORDER, [
            'become' => $become,
        ]);
    }
}
