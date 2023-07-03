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
if ($AccesstokenVarefication === "Allow" && $role == "member") {
    $query = new QueryBuilder('sabade_kharid');
    $mySabad = $query->select('tedad , Mahsulat_id')->where('payed', '=', 'false')->get();

    $data = [];
    foreach ($mySabad as $item) {
        $queryForMahsul = new QueryBuilder('mahsulat');
        $namePrice = $queryForMahsul->select('name , price')->where('Mahsulat_id', '=', $item['Mahsulat_id'])->get();
        $info = [
            'tedad' => $item['tedad'],
            'name' => $namePrice[0]['name'],
            'price' => $namePrice[0]['price'],
        ];
        $data[] = $info;
    }
    echo json_encode($data);
} else {
    echo json_encode($AccesstokenVarefication);
}
