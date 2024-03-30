<?php   // session.php
declare(strict_types=1);
ob_start();

session_start();

var_dump($_SESSION);

$_SESSION['s_test'] = random_int(0, 99);

ob_end_flush();
