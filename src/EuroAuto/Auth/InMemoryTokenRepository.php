<?php

namespace ApiFacade\EuroAuto\Auth;


use ApiFacade\EuroAuto\Helpers\EuroAutoRedisHelper;
use ApiFacade\Shared\Auth\Domain\Authenticate\AuthRepositoryInterface;
use ApiFacade\Shared\Auth\Domain\Token\Token;
use Illuminate\Support\Facades\Redis;
use JsonException;
use Nette\Utils\Json;

class InMemoryTokenRepository implements AuthRepositoryInterface
{

    public function __construct(
        private Redis $redis,
    ) {
    }

    /**
     * @throws \Nette\Utils\JsonException
     */
    public function getToken(): ?Token
    {
        $result = $this->redis::get(EuroAutoRedisHelper::REDIS_AUTH_KEY);

        if (empty($result)) {
            return null;
        }

        return Token::fromState((array) Json::decode($result, 1));
    }

    /**
     * @throws JsonException
     */
    public function setToken(Token $token): void
    {
        $this->redis::set(EuroAutoRedisHelper::REDIS_AUTH_KEY, $token->toJson());
    }

    public function clearToken(): void
    {
        $this->redis::set(EuroAutoRedisHelper::REDIS_AUTH_KEY, null);
    }
}
