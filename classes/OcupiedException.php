<?php

class OcupiedException extends BaseException
{
    #[\JetBrains\PhpStorm\Pure] public function __construct(string $message = "Room is occupied cannot be deleted", int $code = 1337, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}