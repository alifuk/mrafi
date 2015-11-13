<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use AppBundle\Em;
use AppBundle\Entity\User;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('ico_queue', false, false, false, false);

echo ' [*] Waiting for ICO messages. To exit press CTRL+C', "\n";

$callback = function($msg) {

    $json = json_decode($msg->body, true);
    print_r($json);
    $ico = $json["ico"];
    $userId = $json["userId"];

    echo " [x] Received " . $ico . PHP_EOL;
    echo " [x] Received " . $userId . PHP_EOL;

    echo " Starting download from ARES\n";

    $url = 'http://wwwinfo.mfcr.cz/cgi-bin/ares/darv_bas.cgi?ico=' . $ico;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    $data = curl_exec($curl);
    curl_close($curl);

    echo " Data downloaded from ARES\n";

    if ($data) {
        $xml = simplexml_load_string($data);
    }

    if (isset($xml)) {
        $ns = $xml->getDocNamespaces();
        $are = $xml->children($ns['are']);
        if (isset($are->children($ns['D'])->VBAS)) {
            $vbas = $are->children($ns['D'])->VBAS;

            $dic = strval($vbas->DIC);
            $firma = strval($vbas->OF);

            $aa = $vbas->children($ns['D'])->AA;
            $adresa = strval($aa->AT);
            if ($adresa == "") {
                $adresa = strval($vbas->AD->UC) . " " . strval($vbas->AD->PB);
            }

            //$mesto = strval($vbas->RRZ->FU->NFU);

            echo " Parsing done ARES" . PHP_EOL;
        } else {
            echo 'Nonexisting ICO ' . PHP_EOL;
            return;
        }
    } else {
        echo "DB ARES not available" . PHP_EOL;
        return;
    }

    $configuration = new Doctrine\DBAL\Configuration();
    $settings = ['dbname' => 'ifarmnew', 'user' => 'root', 'password' => '', 'host' => 'localhost', 'driver' => 'pdo_mysql', 'charset' => 'utf8'];
    $connection = Doctrine\DBAL\DriverManager::getConnection($settings, $configuration);

    echo "Donwloaded adress " . $adresa . PHP_EOL;
    echo "Donwloaded dic " . $dic . PHP_EOL;

    $ps = $connection->prepare('UPDATE user SET dic=:dic, adress1=:adresa WHERE id=:id ');

    $ps->execute([
        'dic' => $dic,
        'id' => $userId,
        'adresa' => $adresa
    ]);

    echo "Updated: " . $ps->rowCount();
    print_r($ps->getWrappedStatement());

    print_r($ps->errorInfo());

    echo 'DIC and Adress saved' . PHP_EOL;

    $urladresa = urlencode($adresa);
    $geocode = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . $urladresa . '&key=AIzaSyDmPjww9bGInAdOuHn7PzD5WtVT2fx-eP8');
    echo 'DIC and Adress saved\n';
    $geocodeJSON = json_decode($geocode, true);
    $lat = $geocodeJSON["results"][0]["geometry"]["location"]["lat"];
    $lng = $geocodeJSON["results"][0]["geometry"]["location"]["lng"];

    $ps = $connection->prepare('UPDATE user SET lat=:lat, lng=:lng WHERE id=:id ');

    $ps->execute([
        'id' => $userId,
        'lat' => $lat,
        'lng' => $lng
    ]);

    //https://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&key=AIzaSyDmPjww9bGInAdOuHn7PzD5WtVT2fx-eP8
    //https://maps.googleapis.com/maps/api/distancematrix/json?origins=uranov%C3%A1%202011%20%C4%8Desk%C3%A1%20l%C3%ADpa&destinations=praha&mode=driving&key=AIzaSyDmPjww9bGInAdOuHn7PzD5WtVT2fx-eP8
};

$channel->basic_consume('ico_queue', '', false, true, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}