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
if ($AccesstokenVarefication === "Allow" && $role == "Admin") {
    $accessToken = $_POST['accessToken'];
    $AccesstokenVarefication = TokenGenerator::isAccessTokenValid("$accessToken", 'your_secret_key');
    $decoded = JWT::decode("$accessToken", 'your_secret_key', ['HS256']);
    $number = $decoded->phoneNumber;
    $queryForToken = new QueryBuilder('contact');
    $role = $queryForToken->select('role')->where('phone', '=', "$number")->get();
    if ($AccesstokenVarefication === "Allow" && $role == "Admin") {
        $id = $_POST['sabad_id'];
        $query = new QueryBuilder('sabade_kharid');
        $query->where('sabad_id', '=', $id)->delete()->executeDelete();
    } else {
        echo json_encode($AccesstokenVarefication);
    }
} else {
    echo json_encode($AccesstokenVarefication);
}
