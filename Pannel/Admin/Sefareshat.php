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

    $querySabad = new QueryBuilder('sabade_kharid');
    $sabad = $querySabad->select('*')->where('payed', '=', 'true')->get();
    $tahvil = ['tahvilDadeShode' => [], 'tahvilDadeNashode' => []];
    foreach ($sabad as $item) {
        $mahsulId = $item['Mahsulat_id'];
        $queryMahsulat = new QueryBuilder('mahsulat');
        $mahsul = $queryMahsulat->select('*')->where('Mahsulat_id', '=', "$mahsulId")->get();
        $querySefareshat = new QueryBuilder('sending_log');
        $log = $querySefareshat->select('sending_price , name , ostan , city , Adress , codePosti , date , delivered')->where('code_sabad', '=', $item['sabad_id'])->where('delivered', '=', 'true')->get();
        if (!empty($log)) {
            $tahvil['tahvilDadeShode'] = [
                'name' => $log['0']['name'],
                'ostan' => $log['0']['ostan'],
                'city' => $log['0']['city'],
                'Adress' => $log['0']['Adress'],
                'codePosti' => $log['0']['codePosti'],
                'delivered' => $log['0']['delivered'],
                'phone' => $item['phone'],
                'Mahsulat_id' => $mahsul['0']['Mahsulat_id'],
                'Size' => $mahsul['0']['Size'],
                'Weight' => $mahsul['0']['Weight'],
                'Material' => $mahsul['0']['Material'],
                'image' => $mahsul['0']['image'],
                'color' => $mahsul['0']['color'],
                'description' => $mahsul['0']['description'],
                'mojud' => $mahsul['0']['mojud'],
                'nameMahsul' => $mahsul['0']['name'],
                'date' => $log['0']['date'],
                'sending_price' => $log['0']['sending_price'],
            ];
        }
    }
    $querySabadNoTahvil = new QueryBuilder('sabade_kharid');
    $sabadNoDev = $querySabadNoTahvil->select('*')->where('payed', '=', 'true')->get();

    foreach ($sabadNoDev as $item) {

        $mahsulId = $item['Mahsulat_id'];
        $queryMahsulatNoDev = new QueryBuilder('mahsulat');
        $mahsulNoDev = $queryMahsulatNoDev->select('*')->where('Mahsulat_id', '=', "$mahsulId")->get();
        $querySefareshatNoDev = new QueryBuilder('sending_log');
        $sabad_id = $item['sabad_id'];
        $logNoDev = $querySefareshatNoDev->select('sending_price , name , ostan , city , Adress , codePosti , date  ,code_sabad')->where('code_sabad', '=', "4")->where('delivered', '=', 'false')->get();
        if (!empty($logNoDev)) {
            $tahvil['tahvilDadeNashode'] = [
                'name' => $logNoDev['0']['name'],
                'ostan' => $logNoDev['0']['name'],
                'city' => $logNoDev['0']['name'],
                'Adress' => $logNoDev['0']['name'],
                'CodePosti' => $logNoDev['0']['name'],
                'phone' => $item['phone'],
                'Mahsulat_id' => $mahsulNoDev['0']['Mahsulat_id'],
                'Size' => $mahsulNoDev['0']['Size'],
                'Weight' => $mahsulNoDev['0']['Weight'],
                'Material' => $mahsulNoDev['0']['Material'],
                'image' => $mahsulNoDev['0']['image'],
                'color' => $mahsulNoDev['0']['color'],
                'description' => $mahsulNoDev['0']['description'],
                'mojud' => $mahsulNoDev['0']['mojud'],
                'name' => $mahsulNoDev['0']['name'],
                'date' => $logNoDev['0']['date']
            ];
        }
    }
    echo json_encode($tahvil);
} else {
    echo json_encode($AccesstokenVarefication);
}
