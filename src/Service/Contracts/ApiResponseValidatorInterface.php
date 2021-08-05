<?php

namespace Axelbest\SendbirdClient\Service\Contracts;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface ApiResponseValidatorInterface
{
    public function validate(ResponseInterface $response): void;
}
