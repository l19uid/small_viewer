<?php

class ForbiddenException extends BaseException
{
    #[\JetBrains\PhpStorm\Pure] public function __construct(string $message = "Forbidden Try logging out and in again.", int $code = 403, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}