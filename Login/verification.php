<?php

use App\Builder\QueryBuilder;
use App\Builder\TokenGenerator;
use App\Login\class\GetRole;
use App\Login\class\SendingSMS;
use Firebase\JWT\JWT;

require_once "./vendor/autoload.php";
if (isset($_POST['submit'])) {
    $accessToken = $_POST['accessToken'];
    $verificationToken = $_POST['verificationToken'];
    $AccesstokenVarefication = TokenGenerator::isAccessTokenValid("$accessToken", 'your_secret_key');
    //gettingNumberByToken
    $decoded = JWT::decode("$accessToken", 'your_secret_key', ['HS256']);
    $number = $decoded->phoneNumber;
    // for the SMS code
    $secretKey = 'your_secret_key'; // Replace with your secret key
    $code = $_POST["code"]; // Assuming the verification code is sent via POST request
    $sms = new SendingSMS($number, $secretKey);
    $verificationResult = $sms->verifyCode("$code", "$verificationToken");
    if ($AccesstokenVarefication === "Allow" && $verificationResult === "Allow") {

        $query = new QueryBuilder('contacts');
        $query->select('phone')->where('phone', '=', "$number")->get();
        if (empty($query)) {
            $insertingData = [
                'phone' => $number,
                'role' => 'member',
                'loginDate' => date("Y-m-d H:i:s")
            ];
            $query->insert($insertingData)->execute();
        }
        $role = GetRole::getUserRole($number);
        $UserRole = $role["0"]["role"];
        $data = [
            "status" => "Allow",
            "role" => "$UserRole"
        ];
        echo json_encode($data);
    } else {
        $data = [
            "status" => "NotAllow",
            "code" => "incorrect",
            "requireItems" => [
                "accessToken", "verificationToken"
            ]
        ];
        echo json_encode($data);
    }
}
