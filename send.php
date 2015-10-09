<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('ico_queue', false, false, false, false);

$f = fopen('php://stdin', 'r');
$stdmessage = "hi";
while ($line = fgets($f)) {
    $stdmessage = trim($line," \n\r");
    break;
}

fclose($f);

$msg = new AMQPMessage($stdmessage);
$channel->basic_publish($msg, '', 'ico_queue');

echo " [x] Sent '$stdmessage'\n";
$channel->close();
$connection->close();
