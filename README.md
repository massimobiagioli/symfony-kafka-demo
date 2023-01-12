# Symfony Kafka Demo

## Pre-requisites
- Docker

## Use cases

### Send message to device

```bash
make send-to-device payload="{\\\"type\\\":\\\"con\\\",\\\"message\\\":\\\"Connection request \\\"}"
```

### Start device listening

```bash
make start-device-listener
```

### Consume queue

```bash
make start-consumer
```

### Send message from device

```bash
make send-from-device
```

### Check message received from device

```bash
tail -f var/log/inbound.log
```