<?php

namespace App\Tasks\Test;

use limx\phalcon\Cli\Color;
use Traversable;

class InterTask extends \Phalcon\Cli\Task
{

    public function mainAction()
    {
        echo Color::head('Help:'), PHP_EOL;
        echo Color::colorize('  PHP预定义接口测试'), PHP_EOL, PHP_EOL;

        echo Color::head('Usage:'), PHP_EOL;
        echo Color::colorize('  php run Test\\\\Inter [action]', Color::FG_GREEN), PHP_EOL, PHP_EOL;

        echo Color::head('Actions:'), PHP_EOL;
        echo Color::colorize('  iter        IteratorAggregate聚合迭代器测试', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  array       ArrayAccess测试', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  count       Countable测试', Color::FG_GREEN), PHP_EOL;
    }

    public function countAction()
    {
        $item = new Iter2();
        echo Color::colorize("Count=" . count($item)) . PHP_EOL;

        $item = new Cou();
        echo Color::colorize("Count=" . count($item)) . PHP_EOL;
    }

    public function arrayAction()
    {
        echo Color::colorize("让对象可以像数组一样访问", Color::FG_LIGHT_CYAN) . PHP_EOL;
        $obj = new Arr();

        $obj['new'] = 'new value';
        foreach ($obj as $key => $val) {
            echo Color::colorize(sprintf('%s => %s', $key, json_encode($val)), Color::FG_GREEN) . PHP_EOL;
        }

        unset($obj['id']);
        foreach ($obj as $key => $val) {
            echo Color::colorize(sprintf('%s => %s', $key, json_encode($val)), Color::FG_GREEN) . PHP_EOL;
        }

        echo Color::colorize($obj['name'], Color::FG_GREEN) . PHP_EOL;

    }

    public function iterAction()
    {
        echo Color::colorize("普通类", Color::FG_LIGHT_CYAN) . PHP_EOL;
        $items = new Iter2();
        foreach ($items as $key => $item) {
            echo Color::colorize(sprintf('%s => %s', $key, json_encode($item)), Color::FG_GREEN) . PHP_EOL;
        }
        echo Color::colorize("聚合迭代器", Color::FG_LIGHT_CYAN) . PHP_EOL;
        $items = new Iter();
        foreach ($items as $key => $item) {
            echo Color::colorize(sprintf('%s => %s', $key, json_encode($item)), Color::FG_GREEN) . PHP_EOL;
        }
    }

}

class Iter2
{
    public $item = [
        'id' => 1,
        'name' => 'limx',
    ];
    public $id = 2;
    public $name = 'name';
}

class Cou implements \Countable
{
    public $item = [
        'id' => 1,
        'name' => 'limx',
    ];

    public function count()
    {
        // TODO: Implement count() method.
        return count($this->item);
    }
}

class Arr implements \ArrayAccess
{
    public $item = [
        'id' => 1,
        'name' => 'limx',
    ];

    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
        return isset($this->item[$offset]);
    }

    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
        return $this->item[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->item[$offset] = $value;
        // TODO: Implement offsetSet() method.
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
        unset($this->item[$offset]);
    }

}

class Iter implements \IteratorAggregate
{
    public $item = [
        'id' => 1,
        'name' => 'limx',
    ];
    public $id = 2;
    public $name = 'name';

    public function getIterator()
    {
        // TODO: Implement getIterator() method.
        return new \ArrayIterator($this->item);
    }
}

