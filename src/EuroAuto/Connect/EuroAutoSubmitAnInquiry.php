<?php

namespace ApiFacade\EuroAuto\Connect;

use ApiFacade\Shared\Auth\Domain\Authenticate\CredentialsDto;
use ApiFacade\Shared\Infrastructure\ConnectApi\Domain\SubmitAnInquiry;

class EuroAutoSubmitAnInquiry extends SubmitAnInquiry
{
    protected function getCredentialsDto(): CredentialsDto
    {
        return CredentialsDto::fromState([
                'login' => (string) env('EURO_AUTO_LOGIN', ''),
                'password' => (string) env('EURO_AUTO_PASSWORD', ''),
            ]
        );
    }
}
