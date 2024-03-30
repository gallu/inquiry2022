<?php  // static_value.php

class Hoge {
    public function __construct() {
        echo "----" , __METHOD__ , "<br>";
    }
    public function __destruct() {
        echo "----" , __METHOD__ , "<br>";
    }
}

function Foo() {
    // $obj = new Hoge();
    static $obj = null;
    if (null === $obj) {
        $obj = new Hoge();
    }
}

//
echo "Trap 1 <br>";
Foo();
echo "Trap 2 <br>";
Foo();
echo "Trap 3 <br>";
Foo();
echo "Trap 4 <br>";

