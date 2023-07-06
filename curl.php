<?php

// TODO try curl multi handles:
// https://www.php.net/manual/en/function.curl-multi-exec.php

$start = microtime(true);
$output = "";

$i = 0;
while (microtime(true) - $start < 1) {
    $output .= curlout("http://localhost:8999/");
    $i++;
}

$time = microtime(true) - $start;
printf("PHP CURL: Ran %d times in %f seconds.", $i, $time);

function curlout(string $url): string
{
    return file_get_contents($url);
}
