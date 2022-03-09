<?php

use \Firebase\JWT\JWT as FB_JWT;
use \Firebase\JWT\Key as Key;

class Jwt
{
    private static string $key = 'secret_key';
    private static string $iss = 'eurodk.com';
    private static string $aud = 'eurodk.com';

    public static function encodeByData(array $data): ?string
    {
        $dataFields = ['id', 'firstname', 'lastname', 'email'];

        $sendData = [];
        foreach ($dataFields as $field) {
            if (!isset($data[$field])) {
                return null;
            }
            $sendData[$field] = $data[$field];
        }

        $tokenData = [
            "iss" => self::$iss,
            "aud" => self::$aud,
            "iat" => time(),
            "nbf" => time(),
            "data" => $sendData
        ];

        return FB_JWT::encode($tokenData, base64_encode(self::$key), 'HS384');
    }

    public static function decode(string $token): object
    {
        return FB_JWT::decode($token, new Key(base64_encode(self::$key), 'HS384'));
    }
}

?>