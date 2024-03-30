<?php  // sql.php
// DBハンドルの把握
require_once(__DIR__ . '/db.php');
var_dump($dbh);

//
/*
$sql = 'SELECT NOW();';
$res = $dbh->query($sql);
var_dump($res);
var_dump($res->fetchAll());
*/

//
$id = 'admin';
//$id = "';INSERT admin_users(id, pass) VALUES('test', 'test'); --";
// 準備された文(プリペアドステートメント)を用意する
$sql = 'SELECT * FROM admin_users WHERE id = :id;';
$pre = $dbh->prepare($sql);
// プレースホルダに値をバインドする
$pre->bindValue(':id', $id, \PDO::PARAM_STR);
// SQLを実行する
$res = $pre->execute();
// 結果を取得する(selectの時だけ)
var_dump($pre->fetch());



