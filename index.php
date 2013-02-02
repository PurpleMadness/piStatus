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

        /*******************************/
        /**         SETTINGS          **/
        /*******************************/
        $username = 'frank';

        /*******************************/
        /**         UTILS             **/
        /*******************************/

        function readableSeconds($secs)
        {
            $units = array(
                "week"   => 7*24*3600,
                "day"    =>   24*3600,
                "hour"   =>      3600,
                "minute" =>        60,
                "second" =>         1,
            );

            // specifically handle zero
            if ( $secs == 0 ) return "0 seconds";

            $s = "";

            foreach ( $units as $name => $divisor ) {
                if ( $quot = intval($secs / $divisor) ) {
                    $s .= "$quot $name";
                    $s .= (abs($quot) > 1 ? "s" : "") . ", ";
                    $secs -= $quot * $divisor;
                }
            }

            return substr($s, 0, -2);
        }

        function readableSize( $bytes )
        {
            $types = array( 'B', 'KB', 'MB', 'GB', 'TB' );
            for( $i = 0; $bytes >= 1024 && $i < ( count( $types ) -1 ); $bytes /= 1024, $i++ );
            return( round( $bytes, 2 ) . " " . $types[$i] );
        }

        /*******************************/
        /**         TABLE             **/
        /*******************************/

        $toPrint = array();
        $toPrint['Hostname'] = getHostName();
        $toPrint['Server IP'] = "<strong>Local: </strong>" . $_SERVER['SERVER_ADDR'] .
        '<br /><strong>External: </strong>' . file_get_contents('http://ipecho.net/plain');
        $toPrint['PHP Version'] = phpversion();

        $loadAverage = sys_getloadavg();
        $averages = '<strong>1 Minute: </strong>' . $loadAverage[0] .
            '<br/><strong>5 Minutes: </strong>' . $loadAverage[1] .
            '<br/><strong>15 Minutes: </strong>' . $loadAverage[2];
        $toPrint['System load'] = $averages;


        $uptime = explode(' ', file_get_contents('/proc/uptime'));
        $toPrint['Uptime'] = readableSeconds(intval($uptime[0]));

        $freeSpace = disk_free_space('/');
        $totalSpace = disk_total_space('/');
        $toPrint['Disk space free'] = readableSize($freeSpace) . ' /' . ' ' . readableSize($totalSpace);


        $data = explode("\n", file_get_contents("/proc/meminfo"));
        $meminfo = array();
        foreach ($data as $line) {
            list($key, $val) = explode(":", $line);
            $meminfo[$key] = substr(trim($val), 0, strlen(trim($val)) - 3);
        }
        $usedMemory = $meminfo['MemTotal'] - $meminfo['MemFree'];
        $toPrint['Memory used'] = $usedMemory . ' kB / ' . $meminfo['MemTotal'] . ' kB';

        /**         vcgencmd          **/

        $temp = shell_exec('sudo /opt/vc/bin/vcgencmd measure_temp | cut -c "6-9"');
        $toPrint['Temperature'] = $temp . '&deg;C';

        $coreClock = explode('=', shell_exec('sudo /opt/vc/bin/vcgencmd measure_clock core'));
        $armClock = explode('=', shell_exec('sudo /opt/vc/bin/vcgencmd measure_clock arm'));
        $toPrint['GPU clock'] = intval($coreClock[1] / 1000000) . ' MHz';
        $toPrint['CPU clock'] = intval($armClock[1] / 1000000) . ' MHz';

        $voltage = shell_exec('sudo vcgencmd measure_volts | cut -c "6-9"') . ' V';
        $toPrint['Core Voltage'] = $voltage;

        /*******************************/
        /**         PRINTING          **/
        /*******************************/
        foreach ($toPrint as $key => $value)
            echo '<tr><td><strong>' . $key . '</strong></td><td>' . $value . '</td></tr>';
        ?>

    </table>

</div>


</body>
</html>
