<?php

$current_time_string = "10";
$waiting_time_string = "8";
$current_time_int = (int)$current_time_string;
$waiting_time_int = (int)$waiting_time_string;

$hours = $current_time_int + $waiting_time_int;

$timeofday = $hours % 24;

echo $timeofday;
?>
