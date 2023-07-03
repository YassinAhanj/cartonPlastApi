<?php

namespace App\Login\Class;

use Firebase\JWT\JWT;

class SendingSMS
{
    private const URL = 'https://console.melipayamak.com/api/send/shared/1805a4cbca724e0097a21982b03b6744';
    private $number;
    private $secretKey;

    public function __construct($number, $secretKey)
    {
        $this->number = $number;
        $this->secretKey = $secretKey;
    }

    public function sendVerificationCode()
    {
        $code = $this->generateVerificationCode();
        $token = $this->generateVerificationToken($code);
        $data = array('bodyId' => '', 'to' => $this->number, 'args' => [$this->number, $code]); // Update $this->code to $code
        $data_string = json_encode($data);
        $ch = curl_init(self::URL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );
        $result = curl_exec($ch);
        curl_close($ch);

        if ($result === false) {
            throw new \Exception('Failed to send SMS.');
        }
        $data = [
            'token' => $token
        ];
        return $data;
    }

    public function verifyCode($code, $token)
    {
        try {

            $decoded = JWT::decode($token, $this->secretKey, ['HS256']);
            $storedCode = $decoded->code;


            $expirationTime = $decoded->exp;
            $storedNumber = $decoded->number; // Get the phone number from the token
            if ($code == $storedCode && time() <= $expirationTime && $storedNumber == $this->number) {
                return "Allow";
            }
            return "NotAllow";
        } catch (\Exception $e) {
            return "NotAllow" . $e;
        }
    }

    private function generateVerificationCode()
    {
        // Generate the verification code (e.g., using random_int() function)
        $code = random_int(1000, 9999);
        return $code;
    }

    private function generateVerificationToken($code)
    {
        $payload = [
            'number' => $this->number,
            'code' => $code,
            'exp' => time() + (5 * 60) // Token expiration time (5 minutes)
        ];
        $token = JWT::encode($payload, $this->secretKey);
        return $token;
    }
}
