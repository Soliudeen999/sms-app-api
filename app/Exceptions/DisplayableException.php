<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DisplayableException extends Exception
{
    protected string $messagethe;

    /**
     * Create a new DisplayableException instance.
     */
    public function __construct(string $message = '', protected int $statusCode = Response::HTTP_BAD_REQUEST)
    {
        parent::__construct($message);
        $this->messagethe = $message;
    }

    public function report()
    {
        // Return view for displayable exception
    }

    public function render(Request $request): Response|JsonResponse|bool
    {
        return response()->error(message: $this->message, http_code: $this->statusCode);
    }

    /**
     * Get the status code for the exception.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
