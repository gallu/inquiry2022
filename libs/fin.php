<?php   // fin.php
declare(strict_types=1);

// バッファ終了
ob_end_flush();

// 出力
echo $twig->render($template_filename, $context);
