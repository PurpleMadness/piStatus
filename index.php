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

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">


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

        .bigbutton {
          text-align: center;
        }

        .bbtoggle {
          margin: 4px;
          display: inline-block;
        }

        .bbtoggle {
          box-shadow: inset 0 0 35px 5px rgba(0, 0, 0, 0.25), inset 0 2px 1px 1px rgba(255, 255, 255, 0.9), inset 0 -2px 1px 0 rgba(0, 0, 0, 0.25);
          border-radius: 8px;
          background: #ccd0d4;
          position: relative;
          height: 140px;
          width: 140px;
        }
        .bbtoggle:before {
          box-shadow: 0 0 17.5px 8.75px #fff;
          border-radius: 118.3px;
          background: #fff;
          position: absolute;
          margin-left: -50.4px;
          margin-top: -50.4px;
          opacity: 0.2;
          content: "";
          height: 100.8px;
          width: 100.8px;
          left: 50%;
          top: 50%;
        }
        .bbtoggle .bbbutton {
          -webkit-filter: blur(1px);
          -moz-filter: blur(1px);
          filter: blur(1px);
          transition: all 300ms cubic-bezier(0.23, 1, 0.32, 1);
          box-shadow: 0 15px 25px -4px rgba(0, 0, 0, 0.5), inset 0 -3px 4px -1px rgba(0, 0, 0, 0.2), 0 -10px 15px -1px rgba(255, 255, 255, 0.6), inset 0 3px 4px -1px rgba(255, 255, 255, 0.2), inset 0 0 5px 1px rgba(255, 255, 255, 0.8), inset 0 20px 30px 0 rgba(255, 255, 255, 0.2);
          border-radius: 96.32px;
          position: absolute;
          background: #ccd0d4;
          margin-left: -48.16px;
          margin-top: -48.16px;
          display: block;
          height: 96.32px;
          width: 96.32px;
          left: 50%;
          top: 50%;
        }
        .bbtoggle .bblabel {
          transition: color 300ms ease-out;
          text-shadow: 1px 1px 3px #ccd0d4, 0 0 0 rgba(0, 0, 0, 0.8), 1px 1px 4px #fff;
          line-height: 139px;
          text-align: center;
          position: absolute;
          font-weight: 700;
          font-size: 42px;
          display: block;
          opacity: 0.9;
          height: 100%;
          width: 100%;
          color: rgba(0, 0, 0, 0.4);
        }
        .bbtoggle input {
          opacity: 0;
          position: absolute;
          cursor: pointer;
          z-index: 1;
          height: 100%;
          width: 100%;
          left: 0;
          top: 0;
        }
        .bbtoggle input:active ~ .bbbutton {
          box-shadow: 0 15px 25px -4px rgba(0, 0, 0, 0.4), inset 0 -8px 30px 1px rgba(255, 255, 255, 0.9), 0 -10px 15px -1px rgba(255, 255, 255, 0.6), inset 0 8px 25px 0 rgba(0, 0, 0, 0.4), inset 0 0 10px 1px rgba(255, 255, 255, 0.6);
        }
        .bbtoggle input:active ~ .bblabel {
          font-size: 40px;
          color: rgba(0, 0, 0, 0.45);
        }
        .bbtoggle input:checked ~ .bbbutton {
          box-shadow: 0 15px 25px -4px rgba(0, 0, 0, 0.4), inset 0 -8px 25px -1px rgba(255, 255, 255, 0.9), 0 -10px 15px -1px rgba(255, 255, 255, 0.6), inset 0 8px 20px 0 rgba(0, 0, 0, 0.2), inset 0 0 5px 1px rgba(255, 255, 255, 0.6);
        }
        .bbtoggle input:checked ~ .bblabel {
          font-size: 40px;
          color: rgba(0, 0, 0, 0.4);
        }
    </style>
</head>

<body>


<div class="container bg">
    <div class="row">
        <div class="span5">
            <p><img src="images/logo.png"/></p>
        </div>
        <div class="span2">
            <div class="bigbutton">
                <div class="bbtoggle" onclick="javascript:location.reload()">
                    <input type="bbcheckbox">
                    <span class="bbbutton"></span>
                    <span class="bblabel"><i class="fa fa-refresh"></i></span>
                </div>
            </div>
        </div>
        <div class="span5">
            <h1 class="pull-right"><?php echo $rpi->getPiModel(); ?></h1>
        </div>
        
        <hr />
    </div>
    <div class="row">
        <div class="span12">
            <table class="table table-striped">

                <?php

                $toPrint = array();


                $toPrint[] = array('Hostname', $rpi->getHostName());
                $toPrint[] = array('Server IP', '<strong>Local: </strong>' . $rpi->getLocalIP() . '</strong><br /><strong>External: </strong>' . $rpi->getExternalIP());
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
                    $toPrint[] = array('CPU clock', $rpi->getCPUClock() . ' MHz (x'.$rpi->getCPUCores().')');

                    $toPrint[] = array('Core Voltage', $rpi->getVoltage() . ' V');
                }


                foreach ($toPrint as $row){
                    echo '<tr><td><strong>' . $row[0] . '</strong></td><td>' . $row[1] . '</td></tr>';
                }
                ?>

            </table>
        </div>
    </div>

</div>


</body>
</html>
