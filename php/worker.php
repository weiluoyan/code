<?php
$connection = new AMQPConnection([
    'host' => '10.207.27.65',
    'port' => '15672',
    'vhost' => '/',
    'login' => 'admin',
    'password' => '123456'
]);

$connection->connect() or die("Cannot connect to the broker!\n");
$channel = new AMQPChannel($connection);

$exchange = new AMQPExchange($channel);
$exchange->setName('test');
$exchange->setType(AMQP_EX_TYPE_DIRECT);
$exchange->declare();

$queue = new AMQPQueue($channel);
$queue->setName('task_queue1');

$queue->setFlags(AMQP_DURABLE);
$queue->declare();
$queue->bind('test', 'task_queue1');

var_dump('[*] Waiting for messages. To exit press CTRL+C');

while (true) {
    $queue->consume('callback'); $channel->qos(0, 1);
}
$connection->disconnect();
function callback($envelope, $queue)
{
    $msg = $envelope->getBody();
    var_dump("Received:" . $msg);
    sleep(substr_count($msg, '.'));
    $queue->ack($envelope->getDeliveryTag());
}
