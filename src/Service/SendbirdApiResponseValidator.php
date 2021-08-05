<?php

declare(strict_types=1);

namespace Axelbest\SendbirdClient\Service;

use Axelbest\SendbirdClient\Exception\SendbirdInternalServerErrorException;
use Axelbest\SendbirdClient\Exception\SendbirdInvalidRequestException;
use Axelbest\SendbirdClient\Service\Contracts\ApiResponseValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class SendbirdApiResponseValidator implements ApiResponseValidatorInterface
{
    /**
     * @param ResponseInterface $response
     * @throws SendbirdInternalServerErrorException
     * @throws SendbirdInvalidRequestException
     */
    public function validate(ResponseInterface $response): void
    {
        try {
            $responseCode = $response->getStatusCode();
            $responseContent = $response->getContent(false);

        } catch (TransportExceptionInterface | HttpExceptionInterface $exception) {
            throw new SendbirdInternalServerErrorException($exception->getMessage(), $exception->getCode());
        }

        $data = json_decode($responseContent);
        switch ($responseCode) {
            case Response::HTTP_INTERNAL_SERVER_ERROR:
                throw new SendbirdInternalServerErrorException($data->message, $data->code);

            case Response::HTTP_BAD_REQUEST:
                throw new SendbirdInvalidRequestException($data->message, $data->code);
        }
    }
}
