<?php
// +----------------------------------------------------------------------
// | 测试脚本 [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace App\Tasks\Test;

use App\Utils\Log;
use App\Utils\Redis;
use Phalcon\Cli\Task;
use limx\phalcon\Cli\Color;

class FileTask extends Task
{
    public function mainAction()
    {
        echo Color::head('Help:'), PHP_EOL;
        echo Color::colorize('  PHP函数参数测试'), PHP_EOL, PHP_EOL;

        echo Color::head('Usage:'), PHP_EOL;
        echo Color::colorize('  php run Test\\\\File [action]', Color::FG_GREEN), PHP_EOL, PHP_EOL;

        echo Color::head('Actions:'), PHP_EOL;
        echo Color::colorize('  save        文件存储', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  csv         CSV文件解析', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  csvVol      CSV文件电压解析', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  log         日志存储', Color::FG_GREEN), PHP_EOL;
        echo Color::colorize('  image       图片裁剪', Color::FG_GREEN), PHP_EOL;
    }

    public function imageAction()
    {
        $file = ROOT_PATH . '/data/file/logo.png';
        $output = ROOT_PATH . '/data/file/logo1.png';
        $res = $this->imageResize($file, 500, 800, $output);
        print_r($res);

        $output = ROOT_PATH . '/data/file/logo2.png';
        $res = $this->imageResize($file, 500, 200, $output);
        print_r($res);
    }

    protected function imageResize($file, $width = 500, $height = 500, $output = null)
    {
        $image = new \Phalcon\Image\Adapter\Gd($file);
        $r_width = null;
        $r_height = null;

        $r1 = floatval($image->getWidth() / $image->getHeight());
        $r2 = floatval($width / $height);
        Log::info(json_encode([$r1, $r2]));
        if ($r1 > $r2) {

            $offsetX = intval((floatval($image->getWidth() / $image->getHeight()) * $height - $width) / 2);
            $offsetY = 0;
            $r_height = $height;  //
            $master = \Phalcon\Image::HEIGHT;  //
        } else {
            $offsetX = 0;
            $offsetY = intval(floatval(($image->getHeight() / $image->getWidth()) * $width - $height) / 2);
            $r_width = $width;
            $master = \Phalcon\Image::WIDTH;
        }
        Log::info(json_encode([$r_width, $r_height, $master, $width, $height, $offsetX, $offsetY]));
        if (!isset($output)) {
            $output = $file;
        }
        return $image->resize($r_width, $r_height, $master)
            ->crop($width, $height, $offsetX, $offsetY)
            ->save($output);
    }

    public function csvVolAction()
    {
        $file = fopen(ROOT_PATH . '/data/file/SPB150.csv', 'r');
        $prevItem = [];
        $result = [];

        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            if (isset($prevItem[4]) && $data[4] < intval($prevItem[4])) {
                $result[] = ['vol' => $prevItem[1], 'per' => intval($prevItem[4])];
            }
            $prevItem = $data;
        }

        $target = ROOT_PATH . '/data/file/SPB150.php';
        file_put_contents($target, "<?php \nreturn [\n");
        foreach ($result as $item) {
            $str = sprintf("    ['vol' => %s, 'per' => %s],\n", $item['vol'], $item['per']);
            file_put_contents($target, $str, FILE_APPEND);
        }
        file_put_contents($target, "];", FILE_APPEND);
        fclose($file);

        echo Color::success("生成文件成功！");
    }

    public function logAction()
    {
        Log::info("测试信息");
        echo Color::success("日志写入成功");
    }

    public function csvAction()
    {
        $file = fopen(ROOT_PATH . '/data/file/device_key.csv', 'r');
        while ($data = fgetcsv($file)) { //每次读取CSV里面的一行内容
            //print_r($data); //此为一个数组，要获得每一个数据，访问数组下标即可
            $goods_list[] = $data;
        }
        Redis::select(1);

        foreach ($goods_list as $item) {
            if ($item[0] != "") {
                Redis::hset($item[1], 'key1', $item[2]);
                Redis::hset($item[1], 'key2', $item[3]);
                echo Color::colorize($item[1]) . PHP_EOL;
            }
        }

        fclose($file);
    }

    public function saveAction()
    {
        $url = "https://avatars1.githubusercontent.com/u/16648551?v=3&s=460";
        $target = ROOT_PATH . "/public/uploads/";
        $data = file_get_contents($url);
        if (!is_dir($target)) {
            mkdir($target, 0777, true);
        }
        $name = uniqid() . ".jpg";
        return file_put_contents($target . $name, $data);
    }


}