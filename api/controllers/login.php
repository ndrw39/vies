<?php

namespace Api\Controllers;

use \Api\Models\Users;
use \Api\Common\Database;
use \Api\Common\Jwt;

class Login extends BaseController
{
    public static function index(object $request): string
    {
        if (self::validate(['password', 'email']) === false) {
            return self::show(['error' => 'Forbidden'], 403);
        }

        $password = trim($request->password);
        $email = trim($request->email);

        $users = new Users(Database::connect());
        $userData = $users->getList(['email' => $email]);

        if (empty($userData[0]) || !password_verify($password, $userData[0]['password'])) {
            return self::show(['error' => 'Undefined user or password'], 403);
        }

        return json_encode(['success' => true, 'jwt' => Jwt::encodeUser($userData[0])]);
    }
}