<?php  // db.php

try {
    // 接続情報用意
    $host = 'localhost';
    $dbname = 'tech';
    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
    // 認証情報用意
    $user = 'tech';
    $pass = 'tech';
    // オプションの追加
    $options = [
        // 必須
        \PDO::ATTR_EMULATE_PREPARES => false,  // エミュレート無効
        \PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,  // 複文無効
        // お好みで
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,  // エラー時に例外を投げる(好み)
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,  // fetchAllとかで「hash配列」にする(好み)
    ];
    // 接続
    $dbh = new PDO($dsn, $user, $pass, $options);
    //var_dump($dbh);
} catch (\PDOException $e) {
    echo $e->getMessage();
    return ;
} catch (\Throwable $e) {
    echo "その他エラー <br>";
    echo $e->getMessage();
    return ;
}
