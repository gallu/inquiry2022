<?php  // array_add.php
declare(strict_types=1);

$awk_1 = [
    "hoge" => 1,
    "foo" => 2,
];

$awk_2 = [
    "foo" => 222,
    "bar" => 333,
];
//var_dump($awk_1, $awk_2);

//
$add_1 = $awk_1 + $awk_2;
var_dump($add_1);

//
$add_2 = array_merge($awk_1, $awk_2);
var_dump($add_2);

