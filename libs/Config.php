<?php  // Config.php
declare(strict_types=1);

/**
 * config(設定情報)を扱うクラス
 */
class Config {
    // データの取得
    public static function get($key, $default = null) {
        // configの配列の取得
        static $config_data = null; // キャッシュしておく
        if ($config_data === null) {
            // echo "read <br>"; // 確認用
            $environment_config = require_once(BASEPATH . "/environment_config.php");
            $config = require_once(BASEPATH . "/config.php");
            $config_data = $environment_config + $config;
        }

        // keyがあったらその値を、なかったらdefaultをreturnする
        return $config_data[$key] ?? $default;
    }
}