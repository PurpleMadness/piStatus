<?php

$action = $_GET['action'];

if($_GET['action'] == 'pistatusmenu'){
    // requires sudo and www-data in "video" group
    $temp = shell_exec('sudo /opt/vc/bin/vcgencmd measure_temp | cut -c "6-9"');
    $tempString = trim($temp) . " °C";

    $hostname = getHostName();

    $json = array('hostname' => $hostname, 'value' => $tempString);

    echo json_encode($json);
}