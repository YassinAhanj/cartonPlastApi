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
    $builder = new QueryBuilder('contacts');
    $builder->where('Mahsulat_id', '=', $_POST['mahsul_id'])
        ->update([
            'Size' => $_POST['size'],
            'Weight' => $_POST['Weight'],
            'image' => $_POST['image'],
            'color' => $_POST['color'],
            'description' => $_POST['description'],
            'mojud' => $_POST['mojud'],
            'name' => $_POST['name'],
            'price' => $_POST['price']
        ])
        ->execute();
}else{
    echo json_encode($AccesstokenVarefication);
}
