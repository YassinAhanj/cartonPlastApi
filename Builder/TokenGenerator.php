<?php

namespace App\Builder;

use Firebase\JWT\JWT;

class TokenGenerator
{
    public static function generateAccessToken($userId)
    {
        $key = 'your_secret_key';
        $payload = [
            'phoneNumber' => $userId,
            'exp' => time() + (30 * 60),
            'token' => self::generateRandomString()
        ];

        return JWT::encode($payload, $key);
    }

    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomCharIndex = random_int(0, $charactersLength - 1);
            $randomString .= $characters[$randomCharIndex];
        }
        return $randomString;
    }
    public static function isAccessTokenValid($accessToken, $secretKey)
    {
        try {
            $decoded = JWT::decode($accessToken, $secretKey, ['HS256']);
            $expirationTime = $decoded->exp;

            // Check if the token has expired
            if (time() > $expirationTime) {
                return "NotAllow"; // Token has expired
            }

            return "Allow"; // Token is still valid
        } catch (\Exception $e) {
            return "NotAllow"; // Failed to decode the token
        }
    }
}
