<?php

class test
{
    public static function hello()
    {
        echo "HELLO WORLD" . PHP_EOL;
    }

    public function hello2()
    {
        echo "HELLO WORLD 2" . PHP_EOL;
    }

    public static function __callStatic($name, $arguments)
    {
        $cls = new self();
        call_user_func_array([$cls], [$name, $arguments]);
    }
}


$a = new test();
$a->hello();
test::hello();

$a->hello2();
test::hello2();
