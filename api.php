<?php

$action = $_GET['action'];

if($_GET['action'] == 'temp'){
    // requires sudo and www-data in "video" group
    $temp = shell_exec('sudo /opt/vc/bin/vcgencmd measure_temp | cut -c "6-9"');
    echo trim($temp) . " °C";
}