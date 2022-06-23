<?php

namespace ApiFacade\Tests\Auth;

use ApiFacade\EuroAuto\Auth\ConnectTokenApplication;
use ApiFacade\EuroAuto\Auth\InMemoryTokenRepository;
use ApiFacade\Shared\Auth\Applications\AuthenticateInApi;
use ApiFacade\Shared\Auth\Domain\Authenticate\AuthApplicationInterface;
use ApiFacade\Shared\Auth\Domain\Authenticate\AuthRepositoryInterface;
use ApiFacade\Shared\Auth\Domain\Authenticate\CredentialsDto;
use ApiFacade\Shared\Auth\Domain\Token\Token;
use Nette\Utils\JsonException;
use Tests\TestCase;
use Webmozart\Assert\Assert;

class EuroAutoApiTest extends TestCase
{
    private AuthenticateInApi $authenticateInApi;

    /**
     * Тест авторизации
     *
     * @return Token
     */
    public function testAuthApi(): Token
    {
        $credentialsDto = CredentialsDto::fromState([
            'login' => env('EURO_AUTO_LOGIN'),
            'password' => env('EURO_AUTO_PASSWORD'),
        ]);
        $token = $this->authenticateInApi->authenticate($credentialsDto);

        self::assertNotEmpty($token->getToken());
        self::assertTrue($token->getExpires() > time());
        self::assertFalse($token->isRotten());

        return $token;
    }

    /**
     * Тест получение токина из репозитория
     *
     * @depends testAuthApi
     * @throws JsonException
     * @throws \JsonException
     */
    public function testGetToken(Token $inToken): void
    {
        /** @var InMemoryTokenRepository $repository */
        $repository = $this->app->make(InMemoryTokenRepository::class);
        $repository->clearToken();
        self::assertNull($repository->getToken());

        $token = $repository->getToken();
        self::assertNull($token);
        $repository->setToken($inToken);
        $token = $repository->getToken();
        self::assertNotNull($token);
        Assert::notNull($token);
        self::assertNotEmpty($token?->getToken());
        self::assertTrue($token?->getExpires() > time());
        self::assertFalse($token?->isRotten());
        $repository->clearToken();
        self::assertNull($repository->getToken());
    }


    protected function setUp(): void
    {
        parent::setUp();
        $this->app->bind(AuthApplicationInterface::class, ConnectTokenApplication::class);
        $this->app->bind(AuthRepositoryInterface::class, InMemoryTokenRepository::class);
        /** @var AuthenticateInApi $authenticateInApi */
        $authenticateInApi = $this->app->make(AuthenticateInApi::class, [
            'authRepository' => new ConnectTokenApplication()
        ]);
        $this->authenticateInApi = $authenticateInApi;
    }
}
