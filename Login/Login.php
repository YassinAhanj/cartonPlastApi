<?php
require_once "./vendor/autoload.php";

// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Headers: Content-Type');
// header('Access-Control-Allow-Methods: POST');

use App\Builder\TokenGenerator;
use App\Login\class\SendingSMS;

$PhoneNumber = $_POST['data'];
$Errors = [
    "empty" => '',
    'accessToken' => 'Not Found',
    'SMSToken' => 'Not Found'
];
$code = '';
if (isset($_POST['submit'])) {
    if (empty($_POST['data'])) {
        $Errors["empty"] = "لطفا شماره مورد نظر خود را وارد کنید";
        echo json_encode($Errors);
    } else {
        //Sending SMS
        $secretKey = 'your_secret_key'; // Replace with your secret key
        $sms = new SendingSMS("$PhoneNumber", $secretKey);
        $verificationCode = $sms->sendVerificationCode();
        //  Generate access token
        $token = TokenGenerator::generateAccessToken("$PhoneNumber");
        $data = [
            'accessToken' => $token,
            'smsToken' => $verificationCode
        ];
        echo json_encode($data);
    }
}
