<?php  // Config_T.php
declare(strict_types=1);

// 初期処理の読み込み
require_once('./init.php');

// configクラスの読み込み
require_once(BASEPATH . "/libs/Config.php");

// テスト
$val = Config::get("db");
var_dump($val);
$val = Config::get("foo");
var_dump($val);
$val = Config::get("foo", 123);
var_dump($val);
