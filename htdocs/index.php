<?php   // index.php
declare(strict_types=1);

// 初期処理の読み込み
require_once('../libs/init.php');

//
$template_filename = "index.twig";
$context = [];

// 出力処理
require_once(BASEPATH . '/libs/fin.php');
