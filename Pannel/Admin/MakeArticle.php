<?php

use App\Builder\QueryBuilder;
use App\Builder\TokenGenerator;
use Firebase\JWT\JWT;

require_once "./vendor/autoload.php";
$accessToken = $_POST['accessToken'];
$AccesstokenVarefication = TokenGenerator::isAccessTokenValid("$accessToken", 'your_secret_key');
$decoded = JWT::decode("$accessToken", 'your_secret_key', ['HS256']);
$number = $decoded->phoneNumber;
$queryForToken = new QueryBuilder('contact');
$role = $queryForToken->select('role')->where('phone', '=', "$number")->get();
if ($AccesstokenVarefication === "Allow" && $role == "Admin") {
    $uploadDir = "../../../cpi/public/imageArticle";
    $fileName = $_FILES['image']['name'];
    $filePath = $uploadDir . uniqid() . '_' . $fileName;
    if (move_uploaded_file($fileTmpPath, $filePath)) {
        $query = new QueryBuilder('article');
        $data = [
            'Likes' => 0,
            'Dislikes' => 0,
            'image' => $fileName = $fileName,
            'description' => $_POST['description'],
            'title' => $_POST['title']
        ];
        $query->insert($data)->execute();
    } else {
        echo "Failed to move the uploaded file.";
    }
} else {
    echo json_encode($AccesstokenVarefication);
}
