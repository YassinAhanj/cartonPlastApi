<?php
require_once "./vendor/autoload.php";

use App\Builder\QueryBuilder;
use Firebase\JWT\JWT;
use App\Builder\TokenGenerator;

$accessToken = $_POST['accessToken'];
$AccesstokenVarefication = TokenGenerator::isAccessTokenValid("$accessToken", 'your_secret_key');
$decoded = JWT::decode("$accessToken", 'your_secret_key', ['HS256']);
$number = $decoded->phoneNumber;
$queryForToken = new QueryBuilder('contact');
$role = $queryForToken->select('role')->where('phone', '=', "$number")->get();
if ($AccesstokenVarefication === "Allow" && $role == "ceo") {
    $queryForMember = new QueryBuilder('contacts');
    $queryForAdmin = new QueryBuilder('contacts');
    $members = $queryForMember->select('phone , role , loginDate')->where('role', '=', 'member')->get();
    $Admins = $queryForAdmin->select('phone , role , loginDate')->where('role', '=', 'Admin')->get();

    $data = [
        'Members' => $members,
        'Admins' => $Admins,
    ];

    echo json_encode($data);
} else {
    $requires = [
        'accessToken' => "Access Token is not valid"
    ];
}
