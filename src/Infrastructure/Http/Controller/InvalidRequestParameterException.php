<?php

declare(strict_types=1);

namespace Konair\HAP\Payment\Infrastructure\Http\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Response;

final class InvalidRequestParameterException extends Exception
{
    private Response $response;

    public static function create(Response $response): self
    {
        $exception = new self();
        $exception->response = $response;

        return $exception;
    }

    public function response(): Response
    {
        return $this->response;
    }
}
