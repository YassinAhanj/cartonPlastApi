<?php
require_once "./vendor/autoload.php";

use App\Builder\TokenGenerator;
use Firebase\JWT\JWT;
use App\Builder\QueryBuilder;

$accessToken = $_POST['accessToken'];
$AccesstokenVarefication = TokenGenerator::isAccessTokenValid("$accessToken", 'your_secret_key');
$decoded = JWT::decode("$accessToken", 'your_secret_key', ['HS256']);
$number = $decoded->phoneNumber;
$queryForToken = new QueryBuilder('contact');
$role = $queryForToken->select('role')->where('phone', '=', "$number")->get();
if ($AccesstokenVarefication === "Allow" && $role == "ceo") {

    $builder = new QueryBuilder('contacts');
    $builder->where('phone_number', '=', '09146360241')
        ->update(['role' => $_POST['role']])
        ->execute();
} else {
    echo json_encode($AccesstokenVarefication);
}
