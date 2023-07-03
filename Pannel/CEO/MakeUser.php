<?php

use App\Builder\QueryBuilder;
use App\Builder\TokenGenerator;
use Firebase\JWT\JWT;

$accessToken = $_POST['accessToken'];
$AccesstokenVarefication = TokenGenerator::isAccessTokenValid("$accessToken", 'your_secret_key');
$decoded = JWT::decode("$accessToken", 'your_secret_key', ['HS256']);
$number = $decoded->phoneNumber;
$queryForToken = new QueryBuilder('contact');
$role = $queryForToken->select('role')->where('phone', '=', "$number")->get();
if ($AccesstokenVarefication === "Allow" && $role == "ceo") {
    require_once "./vendor/autoload.php";

    $query = new QueryBuilder('contacts');
    $data = [
        "phone" => $_POST['data'],
        "role" => $_POST['role']
    ];
    $query->insert($data)->execute();
}else{
    echo json_encode($AccesstokenVarefication);
}
