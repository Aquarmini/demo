<?php

namespace App\Tasks\Test;

use Illuminate\Support\Collection;
use limx\phalcon\Cli\Color;

class LaravelTask extends \Phalcon\Cli\Task
{

    public function mainAction()
    {
        echo Color::head('Help:'), PHP_EOL;
        echo Color::colorize('  Laravel\\Support扩展测试'), PHP_EOL, PHP_EOL;

        echo Color::head('Usage:'), PHP_EOL;
        echo Color::colorize('  php run Test\\\\Laravel [action]', Color::FG_GREEN), PHP_EOL, PHP_EOL;

        echo Color::head('Actions:'), PHP_EOL;
        echo Color::colorize('  collect        集合测试', Color::FG_GREEN), PHP_EOL;
    }

    public function collectAction()
    {
        $arr = [
            ['id' => 1, 'name' => 'name1'],
            ['id' => 2, 'name' => 'name2'],
            ['id' => 3, 'name' => 'name3'],
            ['id' => 4, 'name' => 'name4'],
            ['id' => 5, 'name' => 'name5'],
            ['id' => 6, 'name' => 'name6'],
            ['id' => 7, 'name' => 'name7'],
        ];
        echo Color::colorize("数据源：") . PHP_EOL;
        echo Color::colorize("  " . json_encode($arr)) . PHP_EOL;
        $collection = new Collection($arr);
        $collection->map(function ($item) {
            echo Color::colorize("map取出每一项：" . json_encode($item), Color::FG_GREEN) . PHP_EOL;
        });
        echo Color::colorize("ID求和：" . $collection->sum('id'), Color::FG_LIGHT_CYAN) . PHP_EOL;
        echo Color::colorize("filter取出ID>5的集合" . $collection->filter(function ($item) {
                return $item['id'] > 5;
            }), Color::FG_GREEN
        );
    }

}

