<?php  // Db_T.php
declare(strict_types=1);

// 初期処理の読み込み
require_once('./init.php');

// Dbクラスの読み込み
require_once(BASEPATH . "/libs/Db.php");

// DB接続テスト
$dbh = Db::getHandle();
var_dump($dbh);
$dbh = Db::getHandle();
var_dump($dbh);
