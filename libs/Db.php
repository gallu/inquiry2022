<?php  // Db.php
declare(strict_types=1);

/**
 */
class Db {
    public static function getHandle(): ?PDO {
        //
        static $dbh = null; // キャッシュ
        if ($dbh === null) {
            try {
                // 接続情報をconfigから取得
                $conf = Config::get("db");
                // 接続情報用意
                $host = $conf["host"];
                $dbname = $conf["dbname"];
                $charset = $conf["charset"];
                $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";
                // 認証情報用意
                $user = $conf["user"];
                $pass = $conf["pass"];
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
            } catch (\PDOException $e) {
                echo $e->getMessage();
                return null;
            } catch (\Throwable $e) {
                echo "その他エラー <br>";
                echo $e->getMessage();
                return null;
            }
        }
        //
        return $dbh;
    }
}
