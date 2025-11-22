<?php

declare(strict_types=1);

namespace App\Utils\Exceptions;

use App\Exceptions\DisplayableException;
use Aws\Exception\AwsException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class ExceptionHandler
{
    public function report(Throwable $exception)
    {
        return match (true) {
            $exception instanceof ThrottleRequestsException => $this->handleThrottleException($exception),
            $exception instanceof RouteNotFoundException => $this->handleRouteNotFound($exception),
            $exception instanceof AuthenticationException => $this->handleAuthenticationException(),
            $exception instanceof AuthorizationException => $this->handleAuthorizationException($exception),
            $exception instanceof AccessDeniedHttpException => $this->handleAccessDeniedHttpException($exception),
            $exception instanceof MethodNotAllowedHttpException => $this->handleMethodNotAllowedHttpException(),
            $exception instanceof ModelNotFoundException => $this->handleModelNotFoundException($exception),
            $exception instanceof DisplayableException => $this->handleDisplayableException($exception),
            $exception instanceof ValidationException => $this->handleValidationException($exception),
            $exception instanceof NotFoundHttpException => $this->handleNotFound($exception),
            $exception instanceof HttpException => $this->handleHttpException($exception),
            default => null,
        };
    }

    public function handleNotFound(NotFoundHttpException $exception)
    {
        $message = 'Htpp Route not Found.';

        if (app()->isProduction()) {
            $message = $exception->getMessage();
        }

        return Response::error(message: $message, http_code: $exception->getStatusCode());
    }

    public function handleRouteNotFound(RouteNotFoundException $exception)
    {
        $message = 'Route not Found.';

        if (! app()->isProduction()) {
            $message = $exception->getMessage();
        }

        return Response::error(message: $message, http_code: Response::HTTP_NOT_FOUND);
    }

    public function handleAuthenticationException()
    {
        return Response::error(message: 'Requesting User is not authenticated', http_code: 401);
    }

    public function handleMethodNotAllowedHttpException()
    {
        return Response::error(message: 'Make sure your are using the right http method', http_code: Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function handleDisplayableException(DisplayableException $exception)
    {
        return Response::error(message: $exception->getMessage(), http_code: $exception->getStatusCode());
    }

    public function handleAuthorizationException(AuthorizationException $exception)
    {
        return Response::error(message: $exception->getMessage(), http_code: Response::HTTP_FORBIDDEN);
    }

    public function handleAccessDeniedHttpException(AccessDeniedHttpException $exception)
    {
        return Response::error(message: $exception->getMessage(), http_code: Response::HTTP_FORBIDDEN);
    }

    public function handleModelNotFoundException(ModelNotFoundException $exception)
    {
        return Response::error(message: 'No resource found for ' . class_basename($exception->getModel()));
    }

    public function handleValidationException(ValidationException $exception)
    {
        $errors = $exception->errors();

        return Response::validationError(error: $errors, message: $exception->getMessage(), http_code: Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function handleThrottleException(ThrottleRequestsException $exception)
    {
        return Response::error(message: $exception->getMessage(), http_code: $exception->getStatusCode(), headers: $exception->getHeaders());
    }

    public function handleHttpException(HttpException $exception)
    {
        if ($exception->getStatusCode() === Response::HTTP_FORBIDDEN) {
            return Response::error(message: $exception->getMessage(), http_code: Response::HTTP_FORBIDDEN);
        }

        // TODO: Enable Later
        // Handle other system exceptions
        // return Response::error(message: $exception->getMessage(), http_code: $exception->getStatusCode());
    }
}
