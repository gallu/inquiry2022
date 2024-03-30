<?php  // detail.php
declare(strict_types=1);

// 初期処理の読み込み
require_once(__DIR__ . '/../../libs/init_admin.php');

// 「指定された１件」の詳細な情報を出力する
$flash = $_SESSION['admin']['flash'] ?? [];
unset($_SESSION['admin']['flash']); // flash なので、速やかに削除する

// パラメタのIDを把握
$id = @strval($_GET["id"] ?? "");
//var_dump($id);
// 軽くvalidate
if ("" === $id) {
    header("Location: ./top.php");
    exit;
}

// 入ってきたidに対応するデータを取得
$datum = Inquiry::find($id);
if (false === $datum) {
    header("Location: ./top.php");
    exit;
}
//var_dump($datum);

// CSRF対策
$csrf_token = Csrf::make();

// 出力
$template_filename = "admin/detail.twig";
$context += [
    // お問い合わせ詳細
    'inquiry' => $datum,
    // flashデータ
    'flash' => $flash,
    // csrfトークン
    'csrf_token' => $csrf_token,
];

// 出力処理
require_once(BASEPATH . '/libs/fin.php');

