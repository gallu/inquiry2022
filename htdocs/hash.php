<?php  // hash.php
//
$s = 'test string';
echo $s , "<br>";

//
$md5 = md5($s);
echo $md5 , "<br>";
//
$sha1 = sha1($s);
echo $sha1 , "<br>";

//
$h = hash('sha1', $s);
echo $h , "<br>";

// 実用で使うんならせめてこの辺
$h = hash('sha256', $s);
echo $h , "<br>";
$h = hash('sha512', $s);
echo $h , "<br>";


