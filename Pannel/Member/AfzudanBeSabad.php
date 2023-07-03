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
if ($AccesstokenVarefication === "Allow" && $role == "Admin") {
    $mahsul_id = $_POST['mahsul_id'];
    $query = new QueryBuilder('sabade_kharid');
    $isExisted = $query->select('sabad_id')->where('Mahsulat_id', '=', $mahsul_id)->where('phone', '=', $number)->get();
    if (empty($isExisted)) {
        $queryInsert = new QueryBuilder('sabade_kharid');
        $data = [
            'phone' => $number,
            'payed' => false,
            'Mahsulat_id' => $mahsul_id
        ];
        $queryInsert->insert($data)->execute();
    } else {
        $querySelect = new QueryBuilder('sabade_kharid');
        $sabadId =   $isExisted[0]["sabad_id"];
        $tedad = $querySelect->select('tedad')->where("sabad_id", '=', "$sabadId")->get();
        $sabadId = $isExisted[0]["sabad_id"];
        $queryInsert = new QueryBuilder('sabade_kharid');
        $newTedad = $tedad[0]["tedad"] + 1;
        $queryInsert->where('sabad_id', '=', $sabadId)->update([
            'tedad' => "$newTedad"
        ])->executeUpdate();
    }
} else {
    echo json_encode($AccesstokenVareficati);
}
