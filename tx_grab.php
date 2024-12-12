<?php
require 'vendor/autoload.php';
require 'config.php';

use InfluxDB2\Client;
use InfluxDB2\Model\WritePrecision;


$client = new Client($influxDBConfig);

$ch = curl_init();
curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt'); // set cookie file to given file
curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt'); // set same file as cookie jar
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_URL, "http://192.168.30.49/api/auth?password=$transmitter_password");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);

if (preg_match("/response login=\"true\"/", $output)) {
    // Fetch parameter values
    $frequency = get_parameter($ch, "transmitter.frequency");
    $fwd_power = get_parameter($ch, "meters.fwd_power");
    $rev_power = get_parameter($ch, "meters.rev_power");
    $pa_temp = get_parameter($ch, "meters.pa_temp");
    $peak_deviation = get_parameter($ch, "meters.peak_deviation");

    $data = "transmitter_data,location=208flynn frequency=$frequency,fwd_power=$fwd_power,rev_power=$rev_power,pa_temp=$pa_temp,peak_deviation=$peak_deviation";

    // Write data to InfluxDB
    $write_api = $client->createWriteApi();

    // $response = $client->writePoints($data);
    $write_api->write($data);
    $write_api->close();

    // print_r($response);
    // Echo the timestamp
    echo date('Y-m-d H:i:s') . ": ";
    echo "Data written to InfluxDB.\n";
} else {
    echo "There was an error authenticating to the transmitter.";
    exit(1);
}

curl_close($ch);

function get_parameter($ch, $parameter_name)
{
    curl_setopt($ch, CURLOPT_URL, "http://192.168.30.49/api/getParameter?id=$parameter_name");
    $output = curl_exec($ch);
    preg_match("/value=\"(.*?)\"/", $output, $matches);
    return $matches[1];
}
