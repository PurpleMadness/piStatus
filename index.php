<?php
include('classes/RaspberryPi.class.php');
include('classes/HumanReadable.class.php');

$rpi = new RaspberryPi();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Raspberry Pi Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="images/favicon.png">

    <style>

        body {
            background-color: #f3f3f3;
        }
        .container {
            margin-top: 20px;
            padding: 10px;
        }

        .bg {
            background-color: #FFFFFF;
            box-shadow: 0px 3px 7px rgba(0,0,0,0.5);
            border-radius: 6px 6px 6px 6px;
            border: 1px solid rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>


<div class="container bg">

    <p><img src="images/logo.png"/></p><br />
    <hr />

    <table class="table table-striped">

        <?php

        $toPrint = array();


        $toPrint[] = array('Hostname', $rpi->getHostName());
        $toPrint[] = array('Server IP', '<strong>Local: </strong>' . $rpi->getLocalIP() . '</strong><br /><strong>External:</strong>' . $rpi->getExternalIP());
        $toPrint[] = array('PHP Version', $rpi->getPHPVersion());

        $loadAverage = $rpi->getLoad();

        $loadAverage = sys_getloadavg();
        $toPrint[] = array('System load', '<strong>1 Minute: </strong>' . $loadAverage[0] .
            '<br/><strong>5 Minutes: </strong>' . $loadAverage[1] .
            '<br/><strong>15 Minutes: </strong>' . $loadAverage[2]);


        $toPrint[] = array('Uptime', HumanReadable::readableSeconds($rpi->getUptime()));

        $toPrint[] = array('Disk space free', HumanReadable::readableSize($rpi->getDiskSpace()) . ' / ' . HumanReadable::readableSize($rpi->getDiskSize()));


        $meminfo = $rpi->getMemInfo();
        $usedMemory = $meminfo['MemTotal'] - $meminfo['MemFree'];
        $toPrint[] = array('Memory used', $usedMemory . ' kB / ' . $meminfo['MemTotal'] . ' kB');

        if($rpi->hasRoot()){
            $toPrint[] = array('Temperature', $rpi->getTemperature() . '&deg;C');

            $toPrint[] = array('GPU clock', $rpi->getGPUClock() . ' MHz');
            $toPrint[] = array('CPU clock', $rpi->getCPUClock() . ' MHz');

            $toPrint[] = array('Core Voltage', $rpi->getVoltage() . ' V');
        }


        /*******************************/
        /**         PRINTING          **/
        /*******************************/
        foreach ($toPrint as $row){
            echo '<tr><td><strong>' . $row[0] . '</strong></td><td>' . $row[1] . '</td></tr>';
        }
        ?>

    </table>

</div>


</body>
</html>
