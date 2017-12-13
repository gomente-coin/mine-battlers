<?php

require __DIR__.'/../vendor/autoload.php';

use Carbon\Carbon;

$previousHash = '0000000000000000000000000000000000000000000000000000000000000000';
$nextHash     = 'ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff';
$target       = '000007ffffffffffffffffffffffffffffffffffffffffffffffffffffffffff';
$nounce       = null;

while (true) {
    $now = Carbon::now();

    echo "[$now] $previousHash, $nounce\n";

    do {
        $nounce   = str_random();
        $nextHash = hash('sha256', hash('sha256', $previousHash.$nounce));
    } while (strcmp($nextHash, $target) > 0);

    $previousHash = $nextHash;
}
