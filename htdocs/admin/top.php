<?php  // top.php
declare(strict_types=1);

// 初期処理の読み込み
require_once(__DIR__ . '/../../libs/init_admin.php');

// お問い合わせの一覧の取得
[$inquiry, $search, $page_num, $newer_flg] = Inquiry::getList($_GET);
//var_dump($inquiry);
//var_dump($page_num);

//
$template_filename = "admin/top.twig";
$context += [
    // お問い合わせ一覧
    'inquiry' => $inquiry,
    // ページネーション用情報
    'older_page_num' => $page_num + 1,
    'newer_page_num' => $page_num - 1,
    'newer_flg' => $newer_flg,
    'search_params' => http_build_query($search),
] + $search;
// var_dump($search);
// var_dump(http_build_query($search));


// 出力処理
require_once(BASEPATH . '/libs/fin.php');
