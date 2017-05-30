<?php

namespace App\Tasks\Test;

use Illuminate\Support\Collection;
use limx\phalcon\Cli\Color;

class LaravelTask extends \Phalcon\Cli\Task
{
    protected $testArr = [
        ['id' => 1, 'name' => 'name1'],
        ['id' => 2, 'name' => 'name2'],
        ['id' => 3, 'name' => 'name3'],
        ['id' => 4, 'name' => 'name4'],
        ['id' => 5, 'name' => 'name5'],
        ['id' => 6, 'name' => 'name6'],
        ['id' => 7, 'name' => 'name7'],
    ];

    public function mainAction()
    {
        echo Color::head('Help:'), PHP_EOL;
        echo Color::colorize('  Laravel\\Support扩展测试'), PHP_EOL, PHP_EOL;

        echo Color::head('Usage:'), PHP_EOL;
        echo Color::colorize('  php run Test\\\\Laravel [action]', Color::FG_GREEN), PHP_EOL, PHP_EOL;

        echo Color::head('Actions:'), PHP_EOL;
        echo Color::colorize('  collect        集合测试', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  remove         集合移除测试', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  chunk          集合分组测试', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  contains       集合是否存在测试', Color::FG_GREEN), PHP_EOL;
    }

    public function containsAction()
    {
        echo Color::colorize("数据源：") . PHP_EOL;
        echo Color::colorize("  " . json_encode($this->testArr)) . PHP_EOL;
        $collection = new Collection($this->testArr);
        $result = $collection->contains(function ($item, $key) {
            return $item['id'] == 7;
        });
        echo Color::colorize("结果：" . json_encode($result), Color::FG_LIGHT_CYAN) . PHP_EOL;
    }

    public function chunkAction()
    {
        echo Color::colorize("数据源：") . PHP_EOL;
        echo Color::colorize("  " . json_encode($this->testArr)) . PHP_EOL;
        $collection = new Collection($this->testArr);
        $result = $collection->chunk(3);
        foreach ($result as $item) {
            echo Color::colorize("结果：" . json_encode($item), Color::FG_LIGHT_CYAN) . PHP_EOL;
        }
        echo PHP_EOL;
        $result = array_chunk($this->testArr, 3);
        foreach ($result as $item) {
            echo Color::colorize("结果：" . json_encode($item), Color::FG_LIGHT_CYAN) . PHP_EOL;
        }
    }

    public function removeAction()
    {
        echo Color::colorize("数据源：") . PHP_EOL;
        echo Color::colorize("  " . json_encode($this->testArr)) . PHP_EOL;
        $collection = new Collection($this->testArr);
        $result = $collection->map(function ($item) {
            $item['uniqid'] = uniqid();
            return $item;
        })->filter(function ($item, $key) {
            return $item['id'] > 5;
        });
        echo Color::colorize("结果：" . json_encode($result), Color::FG_LIGHT_CYAN) . PHP_EOL;
    }

    public function collectAction()
    {
        echo Color::colorize("数据源：") . PHP_EOL;
        echo Color::colorize("  " . json_encode($this->testArr)) . PHP_EOL;
        $collection = new Collection($this->testArr);
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

