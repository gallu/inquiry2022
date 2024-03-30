<?php   // init.php
declare(strict_types=1);

// セッション開始
ob_start();
session_start();

// 基準になるPATHの定義
define('BASEPATH', realpath(__DIR__ . '/../') );

// composerのライブラリを使う準備
require_once(BASEPATH . '/vendor/autoload.php');

// configクラスの読み込み
require_once(BASEPATH . "/libs/Config.php");
// Dbクラスの読み込み
require_once(BASEPATH . "/libs/Db.php");
// Csrfクラスの読み込み
require_once(BASEPATH . "/libs/Csrf.php");
// Inquiryクラスの読み込み
require_once(BASEPATH . "/libs/Inquiry.php");

// Twig使う準備
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
$template_dir = BASEPATH . '/templates/';
$twig = new Environment( new FilesystemLoader($template_dir) );
