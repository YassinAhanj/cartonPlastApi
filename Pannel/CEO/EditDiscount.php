<?php

require_once "./vendor/autoload.php";

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
    $builder = new QueryBuilder('discounts');
    $builder->where('id', '=', $_POST['id'])
        ->update([
            'code' => $_POST['code'],
            'percent' => $_POST['percent'],
        ])
        ->execute();
} else {
    echo json_encode($AccesstokenVarefication);
}
