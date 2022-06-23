<?php

namespace ApiFacade\Shared\Auth\Domain\Authenticate;

use ApiFacade\Shared\Auth\Domain\Token\Token;

interface AuthRepositoryInterface
{
    public function getToken(): ?Token;
    public function setToken(Token $token): void;
    public function clearToken(): void;
}
