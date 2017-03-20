<?php

namespace MyApp\Controllers\Test;

use MyApp\Traits\System\Response;
use MyApp\Traits\Test\Log;

class TraitsController extends \Phalcon\Mvc\Controller
{
    use Log, Response;

    public function indexAction()
    {
        return self::success();
    }

    public function dispatchAction()
    {
        dispatch_error(400, "不加return");
        //        return dispatch_error(401, "加return");
        //        dispatch_error(402, "exit");
        //        exit;
        dispatch_error(403, "不加return");
    }

    public function logAction()
    {
        $date = date("Y-m-d H:i:s");
        self::logInfo($date, 'test.log');
    }

}

