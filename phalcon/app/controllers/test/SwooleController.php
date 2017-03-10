<?php

namespace MyApp\Controllers\Test;

use MyApp\Controllers\Test\ControllerBase;

class SwooleController extends ControllerBase
{

    public function websocketAction()
    {
        return $this->view->render('test/swoole', 'websocket');
    }

}

