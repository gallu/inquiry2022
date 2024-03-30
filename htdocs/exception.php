<?php // exception.php

try {
    $fn = 'dummy.txt';
    //$fn = __DIR__;
    throw new ErrorException("お好きな文言");
    $fp = new SplFileObject($fn, "r");
    var_dump($fp);
} catch(\RuntimeException $e) {
    echo "ファイルないっぽいよ？ <br>";
    var_dump($e->getMessage());
    return ;
} catch(\LogicException $e) {
    echo "ディレクトリ指定したぽいよ？<br>";
    var_dump($e->getMessage());
    return ;
} catch(\Throwable $e) {
    echo "その他エラー<br>";
    var_dump($e->getMessage());
    return ;
}

return;
