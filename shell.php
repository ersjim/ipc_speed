<?php

$start = microtime(true);
$output = "";
$cmd = "./new";

$i = 0;
while (microtime(true) - $start < 1) {
    list($stdout) = shellout("./new");
    $output .= $stdout;
    $i++;
}

$time = microtime(true) - $start;
printf("%d chars written. Ran %d times in %f seconds.", strlen($output), $i, $time);

function shellout(string $cmd): array {
    $descriptors = [
        0 => ['pipe', 'r'],  // stdin
        1 => ['pipe', 'w'],  // stdout
        2 => ['pipe', 'w']   // stderr
    ];
    $fd = proc_open("./new", $descriptors, $pipes);
    if (!is_resource($fd)) {
        return ["", "command '$cmd' does not exist", 127];
    }

    $stdout = stream_get_contents($pipes[1]);
    fclose($pipes[1]);

    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[2]);

    $code = proc_close($fd);

    return [$stdout, $stderr, $code];
}
