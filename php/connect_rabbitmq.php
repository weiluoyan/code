<?php
//配置信息
$conn_args = array(
    'host' => '10.207.27.65',
    'port' => '15672',
    'login' => 'admin',
    'password' => '123456',
    'vhost' => '/'
);

//建立一个到rabbitmq服务区的连接
$conn = new AMQPConnection($conn_args);
if (!$conn->connect()) {
    die('Not connected ' . PHP_EOL);
}

/**
 * 在发消息之前我们需要确认队列是存在的
 * 如果把消息发发送一个不存在的队列，会丢弃这条消息
 * 先创建一个队列，然后把消息发送到这个队列中
 *
 */
$queueName = 'hello';
$channel = new AMQPChannel($conn);
$exchange = new AMQPExchange($channel);

/**
 * 发送消息，一条hello world 的字符串，发送到hello队列
 * 在rabbitmq中，消息是不能直接发送到队列，它需要发送到交换机exchange中
 * 使用默认的交换机，它使用一个空字符串来表示
 * 交换机允许我们指定某条消息需要投递到哪个队列
 * $routeKey参数必须指定为队列的名称 publish(message, $routekey)
 */
$queue = new AMQPQueue($channel);
$queue->setName($queueName);
//需要确认队列是存在的，使用declare 创建一个队列，我们可以运行这个命令很多次，但只有一个队列会创建
$queue->declare();
$message = [
    'name' => 'hello',
    'args' => ["0", "1", "2", "3"],
];
//生产者，向rabbitmq发送消息
$state = $exchange->publish(json_encode($message), 'hello');
if (!$state) {
        echo 'Message not sent', PHP_EOL;
} else {
        echo 'Message sent!', PHP_EOL;
}
//消费者获取消息内容
while ($envelope = $queue->get(AMQP_AUTOACK)) {
        echo ($envelope->isRedelivery()) ? 'Redelivery' : 'New Message';
            echo PHP_EOL;
            echo $envelope->getBody(), PHP_EOL;
}

