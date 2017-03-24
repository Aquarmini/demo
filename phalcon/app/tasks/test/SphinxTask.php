<?php
// +----------------------------------------------------------------------
// | ArgTask php 函数参数相关测试 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lmx0536.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <http://www.lmx0536.cn>
// +----------------------------------------------------------------------
// | Date: 2017/3/2 Time: 下午8:56
// +----------------------------------------------------------------------
namespace MyApp\Tasks\Test;

use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;
use SphinxClient;
use limx\phalcon\DB;

class SphinxTask extends Task
{
    public function mainAction()
    {
        if (!extension_loaded('sphinx')) {
            echo Color::error('The sphinx extension is not installed');
            return;
        }
        echo Color::head('Help:') . PHP_EOL;
        echo Color::colorize('  Sphinx全文检索引擎') . PHP_EOL . PHP_EOL;

        echo Color::head('Usage:') . PHP_EOL;
        echo Color::colorize('  php run Test\\\\Sphinx [action]', Color::FG_GREEN) . PHP_EOL . PHP_EOL;

        echo Color::head('Actions:') . PHP_EOL;
        echo Color::colorize('  compare     --debug     效果对比', Color::FG_GREEN) . PHP_EOL;

    }

    /**
     * @desc   效果对比
     * @author limx
     */
    public function compareAction($params)
    {
        if (count($params) === 0) {
            echo Color::error("请输入搜索关键字");
            return;
        }
        $key = $params[0];
        $debug = false;
        if (isset($params[1]) && $params[1] == '--debug') {
            $debug = true;
        }

        $s = new SphinxClient();
        $s->setServer("127.0.0.1", 9312);

        // SPH_MATCH_ALL	    匹配所有查询词（默认模式）.
        // SPH_MATCH_ANY	    匹配查询词中的任意一个.
        // SPH_MATCH_PHRASE	    将整个查询看作一个词组，要求按顺序完整匹配.
        // SPH_MATCH_BOOLEAN	将查询看作一个布尔表达式.
        // SPH_MATCH_EXTENDED	将查询看作一个Sphinx内部查询语言的表达式.
        // SPH_MATCH_FULLSCAN	使用完全扫描，忽略查询词汇.
        // SPH_MATCH_EXTENDED2	类似 SPH_MATCH_EXTENDED ，并支持评分和权重.

        $s->SetMatchMode(SPH_MATCH_PHRASE);
        $s->setMaxQueryTime(3);
        $s->setLimits(0, 1000);
        $result = $s->query($key, "phalcon_test_sphinx");
        if (!empty($result['matches'])) {
            $btime = microtime(true);
            $matches = $result['matches'];
            $ids = array_keys($matches);
            $str = implode(",", $ids);
            $sql = "SELECT id,user_nicename,signature FROM test_sphinx WHERE id IN ({$str})";
            $res = DB::query($sql);
            $etime = microtime(true);
            // dump($res);
            echo Color::head($key . "搜索结果：") . PHP_EOL;
            $msg = sprintf("  Sphinx搜索耗时%f，搜索结果个数%d", $etime - $btime, count($res));
            echo Color::colorize($msg, Color::FG_LIGHT_GREEN) . PHP_EOL;
            if ($debug) {
                dump($res);
                // echo Color::colorize("  " . json_encode($res)) . PHP_EOL;
            }
        }
        // dump($result);
        // return;

        echo PHP_EOL;
        $btime = microtime(true);
        $sql = "SELECT id,user_nicename,signature FROM test_sphinx WHERE user_nicename LIKE ? OR signature LIKE ?";
        $res = DB::query($sql, ["%$key%", "%$key%"]);
        $etime = microtime(true);
        // dump($res);
        $msg = sprintf("  Mysql搜索耗时%f，搜索结果个数%d", $etime - $btime, count($res));
        echo Color::colorize($msg, Color::FG_LIGHT_GREEN) . PHP_EOL;
        if ($debug) {
            dump($res);
            // echo Color::colorize("  " . json_encode($res)) . PHP_EOL;
        }

    }
}