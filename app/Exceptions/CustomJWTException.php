<?php

namespace App\Exceptions;

use Exception;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;


class CustomJWTException extends Exception implements RendersErrorsExtensions
{
    private $statusCode;

    public function __construct(string $message, int $statusCode) {
      parent::__construct($message);

      $this->statusCode = $statusCode;
    }

    public function isClientSafe(): bool
    {
      return true;
    }

    public function getCategory(): string
    {
      return 'CustomJWTException';
    }

    public function extensionsContent(): array
    {
      return [
        'statusCode' => $this->statusCode
      ];
    }
}
