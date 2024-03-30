<?php  // Csrf.php
declare(strict_types=1);

/**
 * tokenは有効時間なし、１回使い切り、10個まで保存
 */
class Csrf {
    public static function make(): string
    {
        // tokenを作る
        $token = bin2hex(random_bytes(24));

        // sessionに保存する
        $_SESSION['admin']['csrf'] ??= []; // 初回のunknown用対策
        $_SESSION['admin']['csrf'][$token] = true;

        // 最大10個まで
        while(10 < count($_SESSION['admin']['csrf'])) {
            array_shift($_SESSION['admin']['csrf']);
        }
        
        // toeknを返す
        return $token ;
    }

    // token チェック
    public static function check(): bool
    {
        // POSTからとれたtoken
        $token = strval($_POST['csrf_token'] ?? '');
        // 空ならとっととNG
        if ('' === $token) {
            return false;
        }

        // sessionの中にあるか？
        return array_key_exists($token, $_SESSION['admin']['csrf']);
/*
        // sessionの中にあるか？(１回使い捨ての実装)
        $r = array_key_exists($token, $_SESSION['admin']['csrf']);
        unset($_SESSION['admin']['csrf'][$token]); // tokenを消す
        return $r;
*/
    }
}

/*
// csrf tokenを(HTMLに)埋め込む時
$csrf_token = Csrf::make();
$context['csrf_token'] = $csrf_token;

// POSTとかされてきたのをチェックする
if (false === Csrf::check()) {
    // CSRF的にダメぽ
}
*/
