<?php

namespace App\Controllers\Route;

use App\Controllers\Controller;

class IndexController extends Controller
{

    public function indexAction()
    {
        echo "显示路由测试";
    }

    public function groupAction()
    {
        echo "路由分组测试";
    }

}

