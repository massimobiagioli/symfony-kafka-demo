<?php

$conf = new \RdKafka\Conf();
$conf->set('bootstrap.servers', 'kafka:9092');
$conf->set('socket.timeout.ms', (string) 50);
$conf->set('queue.buffering.max.messages', (string) 1000);
$conf->set('max.in.flight.requests.per.connection', (string) 1);

$producer = new \RdKafka\Producer($conf);
$producer->setLogLevel(LOG_DEBUG);

if ($producer->addBrokers("kafka:9092") < 1) {
    echo "Failed adding brokers\n";
    exit;
}

$topicConf = new \RdKafka\TopicConf();
$topicConf->set('message.timeout.ms', (string) 30000);
$topicConf->set('request.required.acks', (string) -1);
$topicConf->set('request.timeout.ms', (string) 5000);

$topic = $producer->newTopic("from-device", $topicConf);

if (!$producer->getMetadata(false, $topic, 2000)) {
    echo "Failed to get metadata, is broker down?\n";
    exit;
}

$message = [
    'type' => 'ack',
    'message' => 'Acknowledge',
];

$topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode($message));

$producer->flush(1000);

echo "Message published\n";