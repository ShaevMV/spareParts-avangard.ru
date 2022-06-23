<?php

namespace ApiFacade\EuroAuto\Cart\Repositories;

use ApiFacade\EuroAuto\Cart\Domain\Cart;
use ApiFacade\EuroAuto\Cart\Entity\CartEntity;
use ApiFacade\EuroAuto\Helpers\EuroAutoRedisHelper;
use ApiFacade\EuroAuto\Responses\Carts\NewCart\CartDto;
use Exception;
use Illuminate\Support\Facades\Redis;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class InMemoryCartRepository implements CartRepositoryInterface
{
    public function __construct(
        private Redis $redis,
    ) {
    }


    /**
     * @throws JsonException
     */
    public function createCart(Cart $cartEntity): bool
    {
        $keyThisCart = EuroAutoRedisHelper::getCartKey($cartEntity->getBecome());
        $json = $cartEntity->toJson();

        return $this->redis::set($keyThisCart, $json);
    }

    /**
     * @return array<string, Cart>
     * @throws JsonException
     */
    public function getList(): array
    {
        $key = EuroAutoRedisHelper::getCartKey('*');
        $becomeKeysList = $this->redis::keys($key);
        $result = [];
        if (null !== $becomeKeysList) {
            foreach ($becomeKeysList as $item) {
                $temp = $this->getCart($item);
                if (null !== $temp) {
                    $result[$temp->getId()] = $temp;
                }
            }
        }

        return $result;
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function getCart(string $keyThisCart): ?Cart
    {
        $jsonCart = $this->redis::get($keyThisCart);
        if (!$jsonCart) {
            return null;
        }

        $data = (array) Json::decode($jsonCart, 1);

        return Cart::fromState($data);
    }

    public function remove(string $become): void
    {
        $key = EuroAutoRedisHelper::getCartKey($become);
        $this->redis::del($key);
    }

    public function removeAll(): void
    {
        $key = EuroAutoRedisHelper::getCartKey('*');
        $becomeKeysList = $this->redis::keys($key);
        foreach ($becomeKeysList as $key) {
            $this->redis::del($key);
        }
    }

    /**
     * @throws JsonException
     */
    public function setCartId(CartDto $cartDto, string $become): bool
    {
        $key = EuroAutoRedisHelper::getCartIdKey($become);
        $json = $cartDto->toJson();

        return $this->redis::set($key, $json);
    }

    /**
     * @throws JsonException
     */
    public function getCartId(string $key): ?CartDto
    {
        $jsonCartId = $this->redis::get($key);
        if (!$jsonCartId) {
            return null;
        }

        $data = (array) Json::decode($jsonCartId, 1);

        return CartDto::fromState($data);
    }
}
