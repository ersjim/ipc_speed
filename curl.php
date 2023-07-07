<?php

// TODO try curl multi handles:
// https://www.php.net/manual/en/function.curl-multi-exec.php

$start = microtime(true);

$i = 0;
$mh = curl_multi_init();
$curl_handles = [];
for ($i = 0; $i < 344; ++$i) {
    $ch = curlout("http://localhost:8999/");
    $curl_handles[] = $ch;
    curl_multi_add_handle($mh, $ch);
}

$output = "";
$active = NULL;
$status = NULL;

do { $status = curl_multi_exec($mh, $active); } while ($status === CURLM_CALL_MULTI_PERFORM);

$i = 0;
$nfails = 0;
while ($active && $status === CURLM_OK) {
    $nready = curl_multi_select($mh);
    // if select() is not available, default to a 1ms sleep:
    if ($nready < 0) {
        usleep(1000);
    }
    do { $status = curl_multi_exec($mh, $active); } while ($status === CURLM_CALL_MULTI_PERFORM);
    while ($info = curl_multi_info_read($mh)) {
        if ($info["result"] === CURLE_OK) {
            $output .= sprintf("%d: %s", $i++, curl_multi_getcontent($info["handle"]));
        } else {
            printf("FAIL #%d, result = %s\n", $nfails++, curl_strerror($info["result"]));
        }
    }
}

for ($i = 0; $i < count($curl_handles); $i++) {
    $ch = $curl_handles[$i];
    curl_multi_remove_handle($mh, $ch);
}
curl_multi_close($mh);

$time = microtime(true) - $start;
printf("PHP CURL: Ran %d times in %f seconds.", $i, $time);
printf(" %d bytes returned\n", strlen($output));
printf("%s\n", $output);


function curlout(string $url)
{
    //return file_get_contents($url);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    return $ch;
    //$result = curl_exec($ch);
    //if ($result === false) {
    //    return -1;
    //} else {
    //    return strlen($result);
    //}
}
