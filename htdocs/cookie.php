<?php   // cookie.php
declare(strict_types=1);
ob_start();

var_dump($_COOKIE);

setcookie('c_test', strval(random_int(0, 99)));

ob_end_flush();
