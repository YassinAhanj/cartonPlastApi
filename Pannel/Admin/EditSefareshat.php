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
    $builder = new QueryBuilder('contacts');
    if (isset($_POST['SubmitFullEdit'])) {
        $builder->where('phone_number', '=', '09146360241')
            ->update(['name' => $_POST['name'], 'ostan' => $_POST['ostan'], 'city' => $_POST['city'], 'Adress' => $_POST['Adress'], 'delivered' => $_POST['delivered']])
            ->execute();
    } else if (isset($_POST['deliverySubmit'])) {
        $builder->where('phone_number', '=', $number)
            ->update(['delivered' => 'true'])
            ->executeUpdate();
    }
} else {
    echo json_encode($AccesstokenVarefication);
}
