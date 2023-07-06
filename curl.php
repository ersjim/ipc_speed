<?php

$start = microtime(true);
$output = "";

$i = 0;
while (microtime(true) - $start < 1) {
    $output .= curlout("http://localhost:8999/");
    $i++;
}

$time = microtime(true) - $start;
printf("%s\n====\nran %d times in %f seconds.", $output, $i, $time);

function curlout(string $url): string
{
    return file_get_contents($url);
}
