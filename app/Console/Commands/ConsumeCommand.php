<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ConsumeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = new AMQPStreamConnection('laravel_rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('laravel', false, false, false, false);


        if (Cache::get('laravel_rabbitmq')){
            $message = Cache::get('laravel_rabbitmq');
            echo "Started cached message.\n";
            echo " [*] Received {$message}\n";
            sleep(substr_count($message, '.'));
            echo " [x] Done\n";
            Cache::forget('laravel_rabbitmq');
        }

        echo "[*] Waiting for messages. To exit press CTRL+C\n";
        $callback = function (AMQPMessage $msg) {
            Cache::set('laravel_rabbitmq', $msg->getBody());
            echo ' [x] Received ', $msg->getBody(), "\n";
            sleep(substr_count($msg->getBody(), '.'));
            echo " [x] Done\n";
            Cache::forget('laravel_rabbitmq');
        };

        $channel->basic_consume('laravel', '', false, true, false, false, $callback);

        try {
            $channel->consume();
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }

        $channel->close();
        $connection->close();
    }
}
