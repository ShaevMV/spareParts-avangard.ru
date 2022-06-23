<?php

namespace ApiFacade\Shared\Auth\Domain\Token;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class Token extends AbstractionEntity
{
    public function __construct(
        /** @var string токен */
        protected string $token,
        /** @var int Время жизни токена */
        protected int $expires
    ) {
    }

    public static function fromState(array $data): Token
    {
        return new self(
            $data['token'],
            $data['expires']
        );
    }

    public function getToken(): string
    {
        return 'Bearer ' . $this->token;
    }

    public function getExpires(): int
    {
        return $this->expires;
    }

    public function isRotten(): bool
    {
        return $this->expires <= time();
    }
}
