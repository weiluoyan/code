<?php
$data = [
    'name' => 'hello',
    'args' => ["01", "1", "2", "3"]];
$message = empty($argv[1]) ? json_encode($data) : ' ' . $argv[1];

$conn_args = array(
    'host' => '10.207.27.65',
    'port' => '15672',
    'login' => 'admin',
    'password' => '123456',
    'vhost' => '/'
);

$connection = new AMQPConnection($conn_args);
if (!$connection->connect()) {
    die('Not connected ' . PHP_EOL);
}


$channel = new AMQPChannel($connection);
$exchange = new AMQPExchange($channel);
$exchange->setName('test');
$queue = new AMQPQueue($channel);
$queue->setName('task_queue1');

$queue->setFlags(AMQP_DURABLE);
$queue->declare();

$exchange->publish($message, 'task_queue1');
var_dump("message: $message");

$connection->disconnect();
