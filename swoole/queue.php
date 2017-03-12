<?php
declare(ticks = 1);
require_once __DIR__ . "/vendor/autoload.php";

use limx\func\Cli\Color;
use limx\tools\LRedis;
use limx\tools\MyPDO;

class QueueTask
{
    // 最大进程数
    protected $maxProcesses = 5;
    // 当前进程数
    protected $process = 0;
    // 消息队列Redis键值
    protected $queueKey = 'phalcon:test:queue';
    // 子进程数到达最大值时的等待时间
    protected $waittime = 1;

    public function mainAction()
    {
        if (!extension_loaded('swoole')) {
            echo Color::error('The swoole extension is not installed');
            return;
        }
        if (empty($this->queueKey)) {
            echo Color::error('Please rewrite the queueKey');
            return;
        }
        // install signal handler for dead kids
        pcntl_signal(SIGCHLD, [$this, "signalHandler"]);
        set_time_limit(0);
        // 实例化Redis实例
        $redis = $this->redisClient();
        while (true) {
            if ($this->process < $this->maxProcesses) {
                $data = $redis->brpop($this->queueKey, 3);//无任务时,阻塞等待
                if (!$data) {
                    continue;
                }
                if ($data[0] != $this->queueKey) {
                    // 消息队列KEY值不匹配
                    continue;
                }
                if (isset($data[1])) {
                    $process = new \swoole_process([$this, 'task']);
                    $process->write($this->rewrite($data[1]));
                    $pid = $process->start();
                    if ($pid === false) {
                        $redis->lpush($this->queueKey, $data[1]);
                    } else {
                        $this->process++;
                    }
                }
            } else {
                if (is_int($this->waittime) && $this->waittime > 0) {
                    sleep($this->waittime);
                }
            }
        }
    }

    /**
     * @desc   子进程
     * @author limx
     * @param \swoole_process $worker
     */
    public function task(\swoole_process $worker)
    {
        swoole_event_add($worker->pipe, function ($pipe) use ($worker) {
            $recv = $worker->read();            //send data to master
            $this->run($recv);
            $worker->exit(0);
            swoole_event_del($pipe);
        });
    }

    /**
     * @desc   主线程中操作数据
     * @tip    主线程中不能实例化DB类，因为Mysql连接会中断
     *         暂时原因不明，可能是会被子线程释放掉
     * @author limx
     * @param $data 消息队列中的数据
     * @return mixed 返回给子线程的数据
     */
    protected function rewrite($data)
    {
        // $config = [
        //     'host' => '127.0.0.1',
        //     'dbname' => 'phalcon',
        //     'user' => 'root',
        //     'pwd' => '910123',
        // ];
        // $db = MyPDO::getInstance($config);
        return $data;
    }

    /**
     * @desc   返回redis实例
     * @author limx
     * @return mixed
     */
    protected function redisClient()
    {
        $config = [
            'host' => '127.0.0.1',
            'auth' => '',
            'port' => '6379',
        ];
        return LRedis::getInstance($config);
    }

    /**
     * @desc   消息队列执行的业务代码
     * @author limx
     * @return mixed
     */
    protected function run($recv)
    {
        $config = [
            'host' => '127.0.0.1',
            'auth' => '',
            'port' => '6379',
        ];
        $redis = LRedis::getInstance($config);
        echo Color::colorize(json_encode($redis->keys("*")), Color::FG_LIGHT_GREEN) . PHP_EOL;


        // $config = [
        //     'host' => '127.0.0.1',
        //     'dbname' => 'phalcon',
        //     'user' => 'root',
        //     'pwd' => '910123',
        // ];
        // $db = MyPDO::getInstance($config);
        // $res = $db->fetch("select * from user where id = ?", [2]);
        // echo Color::colorize(json_encode($res), Color::FG_LIGHT_GREEN) . PHP_EOL;
        sleep(1);
    }

    /**
     * @desc   信号处理方法 回收已经dead的子进程
     * @author limx
     * @param $signo
     */
    private function signalHandler($signo)
    {
        switch ($signo) {
            case SIGCHLD:
                while ($ret = \swoole_process::wait(false)) {
                    $this->process--;
                }
        }
    }
}

$queue = new QueueTask();
$queue->mainAction();