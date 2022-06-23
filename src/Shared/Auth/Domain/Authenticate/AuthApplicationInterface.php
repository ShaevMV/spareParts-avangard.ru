<?php

namespace ApiFacade\Shared\Auth\Domain\Authenticate;

use ApiFacade\Shared\Auth\Domain\Token\Token;

interface AuthApplicationInterface
{
    public function authUser(CredentialsDto $username): Token;
    //public function logoutUser(): void;
}
