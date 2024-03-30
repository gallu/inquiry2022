<?php   // index.php
declare(strict_types=1);

// 初期処理の読み込み
require_once('../../libs/init.php');

//
$flash = $_SESSION['admin']['flash'] ?? [];
unset($_SESSION['admin']['flash']); // flash なので、速やかに削除する

// CSRF対策
$csrf_token = Csrf::make();

//
$template_filename = "admin/index.twig";
$context = [
    'flash' => $flash,
    'csrf_token' => $csrf_token,
];

// 出力処理
require_once(BASEPATH . '/libs/fin.php');
