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
    $query = new QueryBuilder('mahsulat');
    $data = [
        'Size' => $_POST['Size'],
        'Weight' => $_POST['Weight'],
        'Material' => $_POST['Material'],
        'image' => $_POST['image'],
        'color' => $_POST['color'],
        'description' => $_POST['description'],
        'mojud' => $_POST['mojud'],
        'price' => $_POST['price']
    ];
    $query->insert($data)->execute();
} else {
    echo json_encode($AccesstokenVarefication);
}
