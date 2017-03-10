<?php

namespace MyApp\Controllers\Test;

class TraitsController extends \Phalcon\Mvc\Controller
{
    use \MyApp\Traits\System\Response;

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

}

