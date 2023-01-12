<?php

$conf = new \RdKafka\Conf();
$conf->set('bootstrap.servers', 'kafka:9092');
$conf->set('group.id', 'test');
$conf->set('log_level', (string) LOG_DEBUG);

$consumer = new \RdKafka\Consumer($conf);
$consumer->setLogLevel(LOG_DEBUG);
$consumer->addBrokers("kafka:9092");

$topicConf = new \RdKafka\TopicConf();
$topicConf->set('enable.auto.commit', 'true');
$topicConf->set('auto.commit.interval.ms', (string) 100);
$topicConf->set('auto.offset.reset', 'earliest');

$topic = $consumer->newTopic("to-device", $topicConf);
$queue = $consumer->newQueue();
$topic->consumeQueueStart(0, RD_KAFKA_OFFSET_BEGINNING, $queue);

do {
    $message = $queue->consume(1000);

    if ($message === null) {
        continue;
    } elseif ($message->err === RD_KAFKA_RESP_ERR_NO_ERROR) {
        print($message->payload . "\n");
    } elseif ($message->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
        print("ERR - End of partition reached" . "\n");
    } elseif ($message->err === RD_KAFKA_RESP_ERR__TIMED_OUT) {
        print("ERR - Timeout" . "\n");
    } else {
        $topic->consumeStop(0);
        throw new \Exception($message->errstr(), $message->err);
    }

    $consumer->poll(1);
} while (true);

$topic->consumeStop(0);
