<?php

namespace App\Console\Commands;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Illuminate\Console\Command;
use PhpAmqpLib\Message\AMQPMessage;

class PublisherCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:publish-test';

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

        $data = "Sleep................................";
        $msg = new AMQPMessage($data);
        $channel->basic_publish($msg, '', 'laravel');

        echo " [x] Sent {$data}\n";

        $channel->close();
        $connection->close();
    }
}
