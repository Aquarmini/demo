<?php

namespace App\Controllers\Test;

use App\Controllers\Test\Controller;

class SwooleController extends Controller
{

    public function websocketAction()
    {
        return $this->view->render('test/swoole', 'websocket');
    }

}

