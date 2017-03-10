<?php

namespace MyApp\Controllers\Admin;

use MyApp\Controllers\ControllerBase;
use MyApp\Models\Test\User;

class HplusController extends ControllerBase
{

    public function indexAction()
    {
        return $this->view->render('admin/hplus', 'index');
    }

    public function listAction()
    {
        return $this->view->render('admin/hplus', 'list');
    }

    public function pfnListAction()
    {
        $pageSize = $this->request->get('pageSize');
        $pageIndex = $this->request->get('pageIndex');
        $users = User::find([
            'offset' => $pageIndex * $pageSize,
            'limit' => $pageSize,
            'columns' => "name,role_id,id",
        ]);
        $count = User::count();
        return success(['data' => $users, 'count' => $count]);
    }

}

