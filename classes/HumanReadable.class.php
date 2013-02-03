<?php

class HumanReadable
{
    public static function readableSeconds($secs)
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

    public static function readableSize( $bytes )
    {
        $types = array( 'B', 'KB', 'MB', 'GB', 'TB' );
        for($i = 0; $bytes >= 1024 && $i < ( count( $types ) -1 ); $bytes /= 1024, $i++);
        return (round( $bytes, 2 ) . " " . $types[$i] );
    }
}
