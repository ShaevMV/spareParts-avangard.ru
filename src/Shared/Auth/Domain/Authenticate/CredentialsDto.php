<?php

namespace ApiFacade\Shared\Auth\Domain\Authenticate;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class CredentialsDto extends AbstractionEntity
{
    public string $login;
    public string $password;

    public function __construct(
        string $login,
        string $password
    )
    {
        $this->login = $login;
        $this->password = $password;
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['login'],
            $data['password']
        );
    }
}
