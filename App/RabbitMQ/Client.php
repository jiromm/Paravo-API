<?php

namespace App\RabbitMQ;

use App\Config;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Client {
    protected $channel;
    protected $connection;

    public function __construct(Config $config) {
        $config = $config->getConfig()['rabbitmq'];

        $this->connection = new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['password']);
        $this->channel = $this->connection->channel();
    }

    public function send($message) {
        $this->channel->queue_declare('main', false, true, false, false);

        $msg = new AMQPMessage($message, ['delivery_mode' => 2]);

        $this->channel->basic_publish($msg, '', 'main');
    }

    public function __destruct() {
        $this->channel->close();
        $this->connection->close();
    }
}
