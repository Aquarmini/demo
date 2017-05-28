<?php

namespace App\Controllers\Test;

use App\Controllers\Controller;

class ViewController extends Controller
{

    /**
     * @desc   长轮询
     * @author limx
     */
    public function longPollingAction()
    {
        return $this->view->render("test/view", 'long_polling');
    }

    /**
     * @desc   长轮询请求
     * @author limx
     */
    public function pfnLongPollingAction()
    {
        $timed = $this->request->get("timed");
        if (!$timed) {
            return self::error("数据请求有误！");
        }
        for ($i = 0; $i < 6; $i++) {
            $i = rand(0, 100); // 产生一个0-100之间的随机数
            if ($i > 20 && $i < 56) { // 如果随机数在20-56之间就视为有效数据，模拟数据发生变化
                $responseTime = time();
                // 返回数据信息，请求时间、返回数据时间、耗时
                $data = [
                    "result" => $i,
                    "time" => $responseTime - $timed
                ];
                return self::success($data);

            } else { // 模拟没有数据变化，将休眠 hold住连接
                sleep(1);
                return self::error("没有数据");
            }
        }
    }

    public function postAction()
    {
        return $this->view->render("test/view", 'post');
    }

    public function pfnPostDataAction()
    {
        $data = $this->request->get();
        $name = $data['data']['name'];
        return self::success(['request' => $data, 'name' => $name]);
    }

}

