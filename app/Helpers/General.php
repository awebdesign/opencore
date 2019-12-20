<?php
/*
 * Created on Tue Dec 10 2019 by DaRock
 *
 * Aweb Design
 * https://www.awebdesign.ro
 *
 */

if (!function_exists('pre')) {
    function pre($var, $exit = false)
    {
        echo "<pre>" . print_r($var, true) . "</pre>\n";
        if (!empty($exit)) exit();
    }
}
