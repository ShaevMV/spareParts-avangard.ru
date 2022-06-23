<?php

namespace ApiFacade\Shared\Auth\Applications;

use ApiFacade\Shared\Auth\Domain\Authenticate\AuthApplicationInterface;
use ApiFacade\Shared\Auth\Domain\Authenticate\CredentialsDto;
use ApiFacade\Shared\Auth\Domain\Token\Token;

class AuthenticateInApi
{
    public function __construct(private AuthApplicationInterface $authRepository)
    {
    }

    public function authenticate(CredentialsDto $credentialsDto): Token
    {
        return $this->authRepository->authUser($credentialsDto);
    }
}
