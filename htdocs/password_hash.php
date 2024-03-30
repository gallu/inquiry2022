<?php  // password_hash.php

$pass = 'raw pass';

// パスワードを保存する用
$ph = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 12]);
echo $ph , "<br>";

// パスワードを比較する
$r = password_verify('raw pass', $ph);
var_dump($r);
//
$r = password_verify('dummy pass', $ph);
var_dump($r);

