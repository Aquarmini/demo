<?php
namespace MyApp\Tasks\Test;

use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;

class ColorTask extends Task
{
    public function mainAction()
    {
        echo "Hello World", PHP_EOL;
        echo Color::success("Hello World");
        echo Color::error("Hello World");
        echo Color::info("Hello World");
    }

}