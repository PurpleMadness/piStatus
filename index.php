<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Raspberry Pi Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="../assets/ico/favicon.png">

    <style>
        .container {
            margin-top: 20px;
        }
    </style>
</head>

<body>


<div class="container">

    <p><img src="images/logo.png"</p><br />
    <hr />

    <table class="table table-striped">

    <?php

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


    $loadAverage = sys_getloadavg();
	
	echo '<tr><td><strong>Host name:</strong></td><td>' . gethostname() . '</td></tr>';

    echo '<tr><td><strong>System load:</strong></td><td><strong>1 minute:</strong> ' . $loadAverage[0] . '<br /><strong>5 minutes:</strong> ' . $loadAverage[1] . '<br /><strong>15 minutes:</strong> ' . $loadAverage[2] . '</td></tr>';


    $uptime = explode(' ', file_get_contents('/proc/uptime'));
    echo '<tr><td><strong>Uptime: </strong></td><td>' . readableSeconds(intval($uptime[0]));

    $freeSpace = disk_free_space('/');
    echo '<tr><td><strong>Disk space free: </strong></td><td>' . readableSize($freeSpace);


    $data = explode("\n", file_get_contents("/proc/meminfo"));
    $meminfo = array();
    foreach ($data as $line) {
        list($key, $val) = explode(":", $line);
        $meminfo[$key] = substr(trim($val), 0, strlen(trim($val)) - 3);
    }
    $usedMemory = $meminfo['MemTotal'] - $meminfo['MemFree'];
    echo '<tr><td><strong>Memory used:</strong></td><td>' . $usedMemory . ' kB / ' . $meminfo['MemTotal'] . ' kB';

    // start vcgencmd (sudo) commands

    $temp = shell_exec('sudo /opt/vc/bin/vcgencmd measure_temp | cut -c "6-9"');
    echo '<tr><td><strong>Temperature:</strong></td><td>' . $temp . '&deg;C';

    $coreClock = explode('=', shell_exec('sudo /opt/vc/bin/vcgencmd measure_clock core'));
    $armClock = explode('=', shell_exec('sudo /opt/vc/bin/vcgencmd measure_clock arm'));

    echo '<tr><td><strong>GPU clock:</strong></td><td>' . intval($coreClock[1] / 1000000) . ' MHz';

    echo '<tr><td><strong>CPU clock:</strong></td><td>' . intval($armClock[1] / 1000000) . ' MHz';
	
	$voltage = shell_exec('sudo /opt/vc/bin/vcgencmd measure_volts | cut -c "6-9"');
    echo '<tr><td><strong>Core Voltage:</strong></td><td>' . $voltage . ' V';

    ?>

    </table>

</div>


</body>
</html>
