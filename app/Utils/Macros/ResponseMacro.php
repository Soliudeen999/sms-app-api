<?php

declare(strict_types=1);

namespace App\Utils\Macros;

use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;

class ResponseMacro extends BaseMacro
{
    public static function success()
    {

        Response::macro('success', function (
            array|object|null $data = null,
            string $message = 'Request Completed',
            int $http_code = Response::HTTP_OK,
            array $headers = [],
        ) {
            $response_data = [
                'status' => true,
                'message' => $message,
            ];

            if (! is_null($data) || ! empty($data)) {
                $response_data['data'] = is_object($data) ? $data : [...$data];
            }

            return response()->json($response_data, $http_code, $headers);
        });
    }

    public static function facadeSuccess()
    {

        ResponseFactory::macro('success', function (
            array|object|null $data = null,
            string $message = 'Request Completed',
            int $http_code = Response::HTTP_OK,
            array $headers = [],
        ) {
            $response_data = [
                'status' => true,
                'message' => $message,
            ];

            if (! is_null($data) || ! empty($data)) {
                $response_data['data'] = is_object($data) ? $data : [...$data];
            }

            return response()->json($response_data, $http_code, $headers);
        });
    }

    public static function noContent()
    {

        Response::macro('noContent', function (
            array|object|null $data = null,
            string $message = 'Request Completed',
            int $http_code = Response::HTTP_NO_CONTENT,
            array $headers = [],
        ) {
            $response_data = [
                'status' => true,
                'message' => $message,
            ];

            if (! is_null($data) || ! empty($data)) {
                $response_data['data'] = is_object($data) ? $data : [...$data];
            }

            return response()->json($response_data, $http_code, $headers);
        });
    }

    public static function facadeNoContent()
    {

        ResponseFactory::macro('noContent', function (
            array|object|null $data = null,
            string $message = 'Request Completed',
            int $http_code = Response::HTTP_NO_CONTENT,
            array $headers = [],
        ) {
            $response_data = [
                'status' => true,
                'message' => $message,
            ];

            if (! is_null($data) || ! empty($data)) {
                $response_data['data'] = is_object($data) ? $data : [...$data];
            }

            return response()->json($response_data, $http_code, $headers);
        });
    }

    public static function error()
    {

        Response::macro('error', function (
            array|object|null $data = null,
            string $message = 'Error Occurred',
            int $http_code = Response::HTTP_BAD_REQUEST,
            array $headers = [],
        ) {
            $response_data = [
                'status' => false,
                'message' => $message,
            ];

            if (! is_null($data) || ! empty($data)) {
                $response_data['data'] = is_object($data) ? $data : [...$data];
            }

            return response()->json($response_data, $http_code, $headers);
        });
    }

    public static function validationError()
    {

        Response::macro('validationError', function (
            array|object|null $error = null,
            string $message = 'Please check your input and try again.',
            int $http_code = Response::HTTP_UNPROCESSABLE_ENTITY,
            array $headers = [],
        ) {
            $response_data = [
                'status' => false,
                'message' => $message,
            ];

            if (! is_null($error) || ! empty($error)) {
                $response_data['errors'] = is_object($error) ? $error : [...$error];
            }

            return response()->json($response_data, $http_code, $headers);
        });
    }

    public static function facadeError()
    {

        ResponseFactory::macro('error', function (
            array|object|null $data = null,
            string $message = 'Error Occurred',
            int $http_code = Response::HTTP_BAD_REQUEST,
            array $headers = [],
        ) {
            $response_data = [
                'status' => false,
                'message' => $message,
            ];

            if (! is_null($data) || ! empty($data)) {
                $response_data['data'] = is_object($data) ? $data : [...$data];
            }

            return response()->json($response_data, $http_code, $headers);
        });
    }
}
