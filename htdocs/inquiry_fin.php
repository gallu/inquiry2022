<?php  // inquiry_fin.php
declare(strict_types=1);

// 初期処理の読み込み
require_once('../libs/init.php');

// 前ページからのデータの取得
$inquiry_data = $_SESSION['inquiry_data'] ?? null;
var_dump($inquiry_data);
if (null === $inquiry_data) {
    header("Location: ./index.php");
    return ;
}

try {
    // --- DBに問い合わせ情報を格納 ---
    $dbh = Db::getHandle();
    // 準備された文(プリペアドステートメント)を用意する
    $sql = 'INSERT INTO inquiries(name, email, tel, body, created_at)
                   VALUES(:name, :email, :tel, :body, :created_at);';
    $pre = $dbh->prepare($sql);
    //var_dump($pre);
    // プレースホルダに値をバインドする
    foreach(["name", "email", "tel", "body"] as $p) {
        $pre->bindValue(":{$p}", $inquiry_data[$p], \PDO::PARAM_STR);
    }
    $pre->bindValue(":created_at", date(DATE_ATOM), \PDO::PARAM_STR);
    // SQLを実行
    $r = $pre->execute();
    //var_dump($r);
    // SQLがちゃんと発行できたら、データを削除しておく
    unset($_SESSION['inquiry_data']);
} catch (\PDOException $e) {
    echo $e->getMessage();
    return null;
}

// 出力処理
header("Location: ./inquiry_fin_success.php");
