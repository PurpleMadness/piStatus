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

    public static function getPiVersionFromRevision($revision) 
    {
        $revisions = array(
            '0002' => 'Model B (rev 1)',
            '0003' => 'Model B (rev 1)',
            '0004' => 'Model B',
            '0005' => 'Model B',
            '0006' => 'Model B',
            '0007' => 'Model A',
            '0008' => 'Model A',
            '0009' => 'Model A',
            '0010' => 'Model B+',
            '0011' => 'Compute Module',
            '0012' => 'Model A+',
            'a01041' => '2 Model B',
        );

        return 'Raspberry Pi ' . $revisions[$revision];
    }
}
