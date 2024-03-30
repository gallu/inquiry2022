<?php   // dir.php

var_dump( __FILE__ );
echo "<br>";

var_dump( __DIR__ . '/../');
echo "<br>";

var_dump( realpath(__DIR__ . '/../') );
echo "<br>";

define('BASEPATH', realpath(__DIR__ . '/../') );
var_dump( BASEPATH );
