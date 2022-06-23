<?php

namespace ApiFacade\Shared\Infrastructure\ConnectApi\Domain;

use ApiFacade\Shared\Auth\Applications\AuthenticateInApi;
use ApiFacade\Shared\Auth\Domain\Authenticate\AuthRepositoryInterface;
use ApiFacade\Shared\Auth\Domain\Authenticate\CredentialsDto;
use ApiFacade\Shared\Auth\Domain\Token\Token;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Throwable;

abstract class SubmitAnInquiry
{
    private const STATUS_NOT_FOUND = 404;

    protected Token $token;
    protected PendingRequest $connect;

    public function __construct(
        private AuthenticateInApi $authenticateInApi,
        private AuthRepositoryInterface $authRepository,
    ) {
        $token = $this->authRepository->getToken();
        if (is_null($token) || $token->isRotten()) {
            $token = $authenticateInApi->authenticate($this->getCredentialsDto());
            $this->authRepository->setToken($token);
        }

        $this->token = $token;
        $app = $this;
        $this->connect = Http::retry(3, 100, static function ($exception) use ($app) {
            if ($exception instanceof RequestException) {
                $app->runErrorResponse($exception->response);
            }

            return $exception instanceof ConnectionException;
        })->withHeaders(
            $this->getHandler()->toArray()
        );
    }

    abstract protected function getCredentialsDto(): CredentialsDto;

    /**
     * @throws ExceptionNotFound
     * @throws ExceptionConnectApi
     */
    protected function runErrorResponse(Response $response): void
    {
        if (!$response->ok()) {
            $jsonBody = $response->body();
            $message = '';
            try {
                /** @var array<string, array> $body */
                $body = Json::decode($jsonBody, Json::FORCE_ARRAY);
                if (!is_array($body) || !isset($body['data']['error']['message'], $body['data']['message'])) {
                    throw new JsonException('не верный формат '.$jsonBody);
                }


                $message .= $body['data']['error']['message'] ?? null;
                $message .= $body['data']['message'] ?? null;
            } catch (Throwable $throwable) {
                $message .= $throwable->getMessage().' '.$jsonBody;
            }
            $messageException = 'Не получилось подключиться '.
                $response->effectiveUri()?->getHost().
                $response->effectiveUri()?->getPath().
                $response->effectiveUri()?->getQuery().'
                '.$message;

            $status = $response->status();

            if (self::STATUS_NOT_FOUND === $status) {
                throw new ExceptionNotFound($messageException);
            }

            throw new ExceptionConnectApi($messageException);
        }
    }

    public function getHandler(): HandlerDto
    {
        if ($this->token->isRotten()) {
            $this->token = $this->authenticateInApi->authenticate($this->getCredentialsDto());
            $this->authRepository->setToken($this->token);
        }

        return new HandlerDto($this->token->getToken());
    }

    /**
     * @throws ExceptionConnectApi
     * @throws JsonException
     */
    protected function getResultResponse(Response $response): array
    {
        $jsonBody = $response->body();
        /** @var array<string, array> $body */
        $body = Json::decode($jsonBody, Json::FORCE_ARRAY);
        $response->close();

        if (!isset($body['data'])) {
            throw new ExceptionConnectApi('Не верный ответ '.$jsonBody);
        }

        return $body['data'];
    }
}
