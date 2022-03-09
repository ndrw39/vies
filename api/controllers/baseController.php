<?php

namespace Api\Controllers;

abstract class BaseController
{
    abstract public static function index(object $request): string;

    public static function validate(array $requiredFields, string $method = 'POST'): bool
    {
        $success = $_SERVER['REQUEST_METHOD'] === $method;

        foreach ($requiredFields as $field) {
            if ($success === false) {
                return false;
            }
            $success = isset($_REQUEST[$field]);
        }

        return $success;
    }

    public static function show(array $data, int $http_code = 200): string
    {
        http_response_code($http_code);
        $default = [
            'success' => false,
        ];

        $resultArray = array_merge($default, $data);
        return json_encode($resultArray, true);
    }
}