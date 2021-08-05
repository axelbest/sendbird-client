<?php

namespace Axelbest\SendbirdClient\Tests\Service;

use Exception;
use Axelbest\SendbirdClient\Exception\SendbirdInvalidRequestException;
use Axelbest\SendbirdClient\Exception\SendbirdInternalServerErrorException;
use Axelbest\SendbirdClient\Service\SendbirdApiResponseValidator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SendbirdApiResponseValidatorTest extends TestCase
{
    public function httpCodeProvider(): array
    {
        return [
            [400, ['code' => 400, 'message' => 'bad data'], SendbirdInvalidRequestException::class],
            [500, ['code' => 500, 'message' => 'error'], SendbirdInternalServerErrorException::class],
        ];
    }

    /** @dataProvider httpCodeProvider */
    public function testValidate($httpCode, $errorData, $exceptionClass): void
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock
            ->expects(self::once())
            ->method('getStatusCode')
            ->willReturn($httpCode);

        $responseMock
            ->expects(self::once())
            ->method('getContent')
            ->willReturn(json_encode($errorData));

        self::expectException($exceptionClass);
        (new SendbirdApiResponseValidator())->validate($responseMock);
    }

    public function testValidateForExpectedExceptions(): void
    {
        /** @var MockObject|ResponseInterface $responseMock */
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock
            ->expects(self::once())
            ->method('getContent')
            ->willThrowException($this->createMock(ExceptionInterface::class));

        self::expectException(Exception::class);

        $service = new SendbirdApiResponseValidator();
        $service->validate($responseMock);
    }


    public function testValidateWithTransportException(): void
    {
        /** @var MockObject|ResponseInterface $responseMock */
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock
            ->expects(self::once())
            ->method('getStatusCode')
            ->willThrowException($this->createMock(TransportExceptionInterface::class));

        self::expectException(SendbirdInternalServerErrorException::class);

        $service = new SendbirdApiResponseValidator();
        $service->validate($responseMock);
    }

}
