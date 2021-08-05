<?php

declare(strict_types=1);

namespace Axelbest\SendbirdClient\Exception;

use Axelbest\SendbirdClient\Exception\Contracts\SendbirdExceptionInterface;
use Throwable;
use Exception;

class SendbirdInternalServerErrorException extends Exception implements SendbirdExceptionInterface
{
    public function __construct($message = 'Sendbird API: internal server error', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
