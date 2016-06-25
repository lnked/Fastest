<?php

if (!function_exists('__')) {
    function __()
    {
        $args = func_get_args();
        $nargs = func_num_args();
        $trace = debug_backtrace();
        $caller = array_shift($trace);

        $key = $caller['file'].':'.$caller['line'];

        echo '<pre>', $key, "\n";
        for ($i=0; $i<$nargs; $i++)
            echo print_r($args[$i], 1), "\n";
        
        echo '</pre>';
    }
}