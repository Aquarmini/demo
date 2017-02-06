<?php

class test
{
    public static function hello()
    {
        echo "HELLO WORLD" . PHP_EOL;
    }
}

$a = new test();
$a->hello();
test::hello();