<?php  // init_admin.php

declare(strict_types=1);

// 初期処理の読み込み
require_once(__DIR__ . '/init.php');

// 認可処理
if (false === isset($_SESSION['admin']['auth'])) {
    // 認可情報がないんでindexに突き返す
    header("Location: ./index.php");
    exit;
}

// テンプレートに「認可状態」であることを埋め込んでおく
$context = [
    'auth' => true,
];
