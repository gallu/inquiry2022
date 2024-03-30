<?php  // password.php

$pass = 'password';
echo $pass , "<br>";

// XXX 今はダメ
$t = microtime(true);
$md5 = md5($pass);
printf("%f <br>", microtime(true) - $t);
echo $md5 , "<br>";
//
$t = microtime(true);
$sha1 = sha1($pass);
printf("%f <br>", microtime(true) - $t);
echo $sha1 , "<br>";

// ギリギリあり
// ユーザ毎に個別のソルトの作成
$salt = bin2hex(random_bytes(16));
$salted_pass = $salt . $pass;
// ストレッチ
$t = microtime(true);
$sha1 = $salted_pass;
for($i = 0; $i < 10000; ++$i) {
    $sha1 = sha1($sha1);
}
printf("%f <br>", microtime(true) - $t);
echo $sha1 , "<br>";

// PHP的に主流
$t = microtime(true);
$ph = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 12]);
printf("%f <br>", microtime(true) - $t);
echo $ph , "<br>";

