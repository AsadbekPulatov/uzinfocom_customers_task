<?php

require_once __DIR__ . '/../../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('laravel_rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare('topic_log', 'topic', false, true, false); // 3rd parameter: durable = true

list($queue_name, ,) = $channel->queue_declare("", false, true, false, false, false); // 2nd parameter: durable = true

$binding_keys = array_slice($argv, 1);
if (empty($binding_keys)) {
    file_put_contents('php://stderr', "Usage: $argv[0] [binding_key]\n");
    exit(1);
}

foreach ($binding_keys as $binding_key) {
    $channel->queue_bind($queue_name, 'topic_log', $binding_key);
}

echo " [*] Waiting for logs. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] ', $msg->getRoutingKey(), ':', $msg->getBody(), "\n";
};

$channel->basic_consume($queue_name, '', false, true, false, false, $callback);

try {
    while ($channel->is_consuming()) {
        $channel->wait();
    }
} catch (\Throwable $exception) {
    echo $exception->getMessage();
}

$channel->close();
$connection->close();
