<?php  // reply.php
declare(strict_types=1);

// 初期処理の読み込み
require_once(__DIR__ . '/../../libs/init_admin.php');

// 返信対象のデータのidを取得する
$id = @strval($_POST["id"] ?? "");
//var_dump($id);
// 軽くvalidate
if ("" === $id) {
    header("Location: ./top.php");
    exit;
}

// CSRFチェック
if (false === Csrf::check()) {
    // エラー処理（topに戻す)
    $_SESSION['admin']['flash']['error']['csrf'] = true;
    header("Location: ./detail.php?id=" . rawurlencode($id));
    exit;
}

// validate2: こちらは「入力ページ」に突き返す
if ( ("" === ($_POST["reply_charge"] ?? ""))
     || ("" === ($_POST["reply_subject"] ?? ""))
     || ("" === ($_POST["reply_body"] ?? ""))
   ) {
    //
    $_SESSION['admin']['flash']['error']['validate'] = true;
    $_SESSION['admin']['flash']['datum'] = [
        'reply_charge' => ($_POST["reply_charge"] ?? ""),
        'reply_subject' => ($_POST["reply_subject"] ?? ""),
        'reply_body' => ($_POST["reply_body"] ?? ""),
    ];
    header("Location: ./detail.php?id=" . rawurlencode($id) . "#reply");
    exit;
}

// DBハンドル取得
$dbh = Db::getHandle();
// トランザクション開始
$dbh->beginTransaction();

// 返信対象のデータを把握する
$datum = Inquiry::find($id);
if (false === $datum) {
    header("Location: ./top.php");
    exit;
}
var_dump($datum);

// 既に返信済なら、変更の上書きはしない
if (null !== $datum["reply_at"]) {
    $_SESSION['admin']['flash']['error']['already_replied'] = true;
    header("Location: ./detail.php?id=" . rawurlencode($id) . "#reply");
    exit;
}

// 返信内容をDBに書き込む(Modelに移動させる)
// プリペアドステートメントを作成
$sql = 'UPDATE inquiries
        SET
            reply_at=:reply_at
            , reply_charge=:reply_charge
            , reply_subject=:reply_subject
            , reply_body=:reply_body
        WHERE
            inquiry_id=:inquiry_id
        ;';
$pre = $dbh->prepare($sql);
// 値をバインド
$pre->bindValue(":reply_at", date(DATE_ATOM));
$pre->bindValue(":reply_charge", $_POST["reply_charge"]);
$pre->bindValue(":reply_subject", $_POST["reply_subject"]);
$pre->bindValue(":reply_body", $_POST["reply_body"]);
$pre->bindValue(":inquiry_id", $id);
// SQLを実行
$r = $pre->execute();
var_dump($r);

// トランザクション終了
$dbh->commit();


// mailで返信する(ダミー)

// おわったらdetailに戻す
$_SESSION['admin']['flash']['message']['reply_success'] = true;
header("Location: ./detail.php?id=" . rawurlencode($id));
exit;
