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
    $mahsulId = $_POST['mahsulId'];
    $query = new QueryBuilder('mahsulat');
    $builder->where('Mahsulat_id', '=', "$mahsulId")->delete()->executeDelete();
} else {
    echo json_encode($AccesstokenVarefication);
}
