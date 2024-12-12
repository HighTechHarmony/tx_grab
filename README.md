# TX_grab

Get transmitter readings from a BW Broadcast transmitter and insert them into an influxdb v2 bucket.

## Configuration

```
<?php

$transmitter_password = "bwbroadcast_transmitter_pw";

// Database connection parameters
$influxDBConfig = [
    'url' => 'http://localhost:8086',
    'token' => 'influxdb_api_token',
    'bucket' => 'influxdb_bucket_name',
    'org' => 'MyOrgName',
    'precision' => InfluxDB2\Model\WritePrecision::NS,
];
```

## Installation

### Dependencies

`composer require influxdata/influxdb-client-php guzzlehttp/guzzle`

### Cron

`*/1 * * * * /usr/bin/php /path/to/tx_grab.php >> /path/to/tx_grab.log`
