<?php   // inquiry_confirm.php
declare(strict_types=1);

// 初期処理の読み込み
require_once('../libs/init.php');

//var_dump($_POST);

// データを取得
// (第一種)ホワイトリスト
$params = [
    'name',
    'email',
    'tel',
    'body',
];
$data = [];
foreach($params as $p) {
    $data[$p] = strval($_POST[$p] ?? '');
}
//var_dump($data);

// validate
$error_messages = [];
// 必須チェック
if ($data['name'] === '') {
    $error_messages['name'] = 'お名前は必須入力です';
}
if ($data['body'] === '') {
    $error_messages['body'] = '問い合わせ内容は必須入力です';
}
// emailのフォーマットチェック
if ( ($data['email'] !== '') && (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false)) {
    $error_messages['email'] = 'emailアドレスのフォーマットエラーです';
}
// 最大長チェック
if (mb_strlen($data['body']) > 10000) {
    $error_messages['body'] = '問い合わせ内容は1万文字以内でお願いします';
}
//var_dump($error_messages);

if ($error_messages !== []) {
    // エラー処理
    // XXX 後回し
    var_dump($error_messages);
    exit;
}

// セッションにデータを格納
$_SESSION['inquiry_data'] = $data;

// 確認画面を表示
$template_filename = "inquiry_confirm.twig";
$context = [
    'data' => $data,
];

// 出力処理
require_once(BASEPATH . '/libs/fin.php');
