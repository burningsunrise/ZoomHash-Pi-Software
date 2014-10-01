<?php
$filename = '/var/www/cpu-miner/miner.log';  //Where the log file is
$output = shell_exec('sudo tail -n18 ' . $filename);  //Only print 18 lines of the log
echo str_replace(PHP_EOL, '<br />', $output);         //Add spacing
?>