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
    $querySelectLike = new QueryBuilder('likearticle');
    $querySelectDiss = new QueryBuilder('disslike');
    $likeId = $querySelectLike->select('id')->where('Phone', '=', $number)->where('Article_id', '=', $_POST['Article_id'])->get();
    $disslikeId = $querySelectDiss->select('id')->where('Phone', '=', $number)->where('Article_id', '=', $_POST['Article_id'])->get();

    if (empty($likeId)) {
        if (!empty($disslikeId)) {
            $query = new QueryBuilder('disslike');
            $builder->where('id', '=', "$disslikeId")->delete()->executeDelete();
        }
        $query = new QueryBuilder('likearticle');
        $data = [
            'Likes' => $number,
            'Article_id' => $_POST['Article_id']
        ];
        $query->insert($data)->execute();
    }
} else {
    echo json_encode($AccesstokenVarefication);
}
