<?php

namespace App\Controllers\Test;

use App\Controllers\Test\ControllerBase;

class SwooleController extends ControllerBase
{

    public function websocketAction()
    {
        return $this->view->render('test/swoole', 'websocket');
    }

}

