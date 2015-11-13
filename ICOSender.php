<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$f = fopen('php://stdin', 'r');
while ($ICO = fgets($f)) {

    //rabbit MQ
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();

    $channel->queue_declare('ico_queue', false, false, false, false);
    
    $msg = new AMQPMessage($ICO);
    $channel->basic_publish($msg, '', 'ico_queue');

    echo(" [x] Sent '$ICO'" . PHP_EOL);
    $channel->close();
    $connection->close();
}

fclose($f);



