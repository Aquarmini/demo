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

    public function nameAction()
    {
        // dump($this->router->getRoutes());exit;
        // dump($this->router->getRouteByName('route.index.target'));exit;
        $url = $this->url->get(['for' => 'route.index.target']);
        // return $this->response->redirect(['for' => 'route.index.target']);
        echo "路由命名测试: " . $url;

        return $this->view->render('test/view', 'name');
    }

    public function targetAction()
    {
        echo "路由命名测试";
    }

}

