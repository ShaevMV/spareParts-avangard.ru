<?php

namespace ApiFacade\EuroAuto\Cart\Repositories;

use ApiFacade\EuroAuto\Cart\Domain\Cart;
use ApiFacade\EuroAuto\Responses\Carts\NewCart\CartDto;

interface CartRepositoryInterface
{
    public function createCart(Cart $cartEntity): bool;
    public function getCart(string $keyThisCart): ?Cart;
    public function getList(): array;
    public function remove(string $become): void;
    public function removeAll(): void;
    public function setCartId(CartDto $cartDto, string $become): bool;
    public function getCartId(string $key): ?CartDto;
}
