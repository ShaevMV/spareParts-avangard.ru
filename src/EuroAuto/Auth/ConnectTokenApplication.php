<?php

namespace ApiFacade\EuroAuto\Auth;

use ApiFacade\EuroAuto\Helpers\EuroAutoHelper;
use ApiFacade\Shared\Auth\Domain\Authenticate\AuthApplicationInterface;
use ApiFacade\Shared\Auth\Domain\Authenticate\CredentialsDto;
use ApiFacade\Shared\Auth\Domain\Authenticate\ExceptionAuth;
use ApiFacade\Shared\Auth\Domain\Token\Token;
use ApiFacade\Shared\Infrastructure\ConnectApi\Domain\ExceptionConnectApi;
use ApiFacade\Shared\Infrastructure\ConnectApi\Domain\ExceptionResponse;
use DomainException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class ConnectTokenApplication implements AuthApplicationInterface
{
    private PendingRequest $connect;

    public function __construct()
    {
        $this->connect = Http::asForm();
    }

    /**
     * @throws ExceptionConnectApi
     * @throws JsonException
     * @throws ExceptionAuth
     * @throws ExceptionResponse
     */
    public function authUser(CredentialsDto $username): Token
    {
        $url = EuroAutoHelper::getUrl(EuroAutoHelper::AUTH_URL);
        $value = $username->toArray();
        $response = $this->connect->post($url, $value);

        if (!$this->validate($response->status())) {
            $response->close();
            throw new DomainException('Системная ошибка');
        }
        $result = (array) Json::decode($response->body(), 1);
        $response->close();

        if (!isset($result['data'])) {
            throw new ExceptionResponse('Не верный формат ответа');
        }

        return Token::fromState((array) $result['data']);
    }

    /**
     * @throws ExceptionConnectApi
     * @throws ExceptionAuth
     */
    public function validate(int $code): bool
    {
        if ($code === 403) {
            throw new ExceptionAuth('Не верные данные для авторизации');
        }

        if ($code !== 200) {
            throw new ExceptionConnectApi('Не получилось подключиться');
        }

        return true;
    }
}
