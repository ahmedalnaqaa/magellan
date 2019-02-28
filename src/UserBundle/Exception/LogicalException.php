<?php

namespace UserBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class LogicalException extends HttpException
{
    public function __construct($message = null, $statusCode = 400, \Exception $previous = null, array $headers = array(), $code = 0)
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
