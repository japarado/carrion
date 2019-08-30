<?php 

namespace App\Utils;

class Utils 
{
    public static function debugArray(array $array, $will_die = true)
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
        $will_die ? die() : null;
    }
}
