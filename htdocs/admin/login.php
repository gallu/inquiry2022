<?php  // login.php
declare(strict_types=1);

// 初期処理の読み込み
require_once('../../libs/init.php');

/*
 * 認証処理
 */
try {
    // DBハンドル取得
    $dbh = Db::getHandle();
    //var_dump($dbh); exit;

    // CSRFチェック
    if (false === Csrf::check()) {
        // エラー処理（indexに戻す)
        throw new \RuntimeException('');
    }

    // 入力データ取得
    // $id = (string)($_POST['id'] ?? '');
    $id = strval($_POST['id'] ?? '');
    $pw = strval($_POST['pw'] ?? '');
    //var_dump($id, $pw); exit;
    // ざっくりvalidate
    if ( ("" === $id)||("" === $pw) ) {
        // エラー処理（indexに戻す)
        throw new \RuntimeException('');
    }

    // トランザクション開始
    $dbh->beginTransaction();

    // idからhashedパスワード把握
    // プリペアドステートメント(準備された文)を用意
    $sql = 'SELECT * FROM admin_users WHERE login_id = :login_id FOR UPDATE;';
    $pre = $dbh->prepare($sql);
    //var_dump($pre); exit;
    // 値をバインド
    $pre->bindValue(":login_id", $id, \PDO::PARAM_STR);
    // 実行
    $r = $pre->execute();
    //var_dump($r); exit;
    $admin_user = $pre->fetch();
    // ユーザが存在しなければエラー
    if (false === $admin_user) {
        // エラー処理（indexに戻す)
        throw new \RuntimeException('');
    }
    //var_dump($admin_user); exit;

    // ログインロックされていたらパスワード比較をせずにNGとする
    if (
        (null !== $admin_user['lock_datetime'])
        &&
        (strtotime($admin_user['lock_datetime']) > time())
        )
        {
echo "ロック中<br>";
        // エラー処理（indexに戻す)
        throw new \RuntimeException('');
    }

    // パスワードを比較
    if (false === password_verify($pw, $admin_user['password'])) {
        // error_countをインクリメントする
        $admin_user['error_count'] ++;
        // SQL(UPDATE)を発行する
        // プリペアドステートメント(準備された文)を用意
        $sql = 'UPDATE admin_users SET error_count=:error_count WHERE admin_user_id=:admin_user_id;';
        $pre = $dbh->prepare($sql);
        //var_dump($pre); exit;
        // 値をバインド
        $pre->bindValue(":error_count", $admin_user['error_count'], \PDO::PARAM_INT);
        $pre->bindValue(":admin_user_id", $admin_user['admin_user_id'], \PDO::PARAM_INT);
        // 実行
        $r = $pre->execute();

        // 一定回数以上エラーがあったらロックする
        if (5 <= $admin_user['error_count']) {
            // SQL(UPDATE)を発行する
            // プリペアドステートメント(準備された文)を用意
            $sql = 'UPDATE admin_users SET lock_datetime=:lock_datetime, error_count=0 WHERE admin_user_id=:admin_user_id;';
            $pre = $dbh->prepare($sql);
            //var_dump($pre); exit;
            // 値をバインド
            $pre->bindValue(":lock_datetime", date(DATE_ATOM, time() + 3600), \PDO::PARAM_STR);
            $pre->bindValue(":admin_user_id", $admin_user['admin_user_id'], \PDO::PARAM_INT);
            // 実行
            $r = $pre->execute();
        }

        // エラー処理（indexに戻す)
        throw new \RuntimeException('');
    }
} catch(\PDOException $e) {
    // DB関連の例外
    echo "DB関連でエラー <br>";
    echo $e->getMessage();
    exit;
} catch(\RuntimeException $e) {
    // トランザクション終了
    if ($dbh->inTransaction()) {
        $dbh->commit();
    }

    // 引きまわす情報をflashセッションにセット
    $_SESSION['admin']['flash']['auth_error'] = true;
    $_SESSION['admin']['flash']['id'] = $id;
    // 非ログインTopPageに移動
    header('Location: ./index.php');
    exit;
}

// ここまできたら「認証できた」
// エラーカウントが１以上なら、正常に認証したので一通りクリアにしておく
if (0 < $admin_user['error_count']) {
    // SQL(UPDATE)を発行する
    // プリペアドステートメント(準備された文)を用意
    $sql = 'UPDATE admin_users SET error_count=0, lock_datetime=null WHERE admin_user_id=:admin_user_id;';
    $pre = $dbh->prepare($sql);
    //var_dump($pre); exit;
    // 値をバインド
    $pre->bindValue(":admin_user_id", $admin_user['admin_user_id'], \PDO::PARAM_INT);
    // 実行
    $r = $pre->execute();
}

// トランザクション終了
$dbh->commit();

/*
 * 認可処理用の仕組
 */
// セキュリティ対策
session_regenerate_id(true);
 // 認可用の情報を突っ込む
unset($admin_user['password']); // パスワードはさすがに消しておく
$_SESSION['admin']['auth']['user'] = $admin_user;

// ログイン成功なのでtop画面に遷移
header('Location: ./top.php');
