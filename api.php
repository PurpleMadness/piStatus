<?php
include('classes/RaspberryPi.class.php');
include('classes/HumanReadable.class.php');

$rpi = new RaspberryPi();

$action = $_GET['action'];

if($_GET['action'] == 'pistatusmenu'){
    // requires sudo and www-data in "video" group
    $temp = $rpi->getTemperature();
    $tempString = trim($temp) . " °C";

    $hostname = $rpi->getHostName();

    $json = array('hostname' => $hostname, 'value' => $tempString);

    echo json_encode($json);
}