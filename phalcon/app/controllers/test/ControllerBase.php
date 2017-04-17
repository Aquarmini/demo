<?php

namespace App\Controllers\Test;

use Phalcon\Mvc\Controller;
use App\Traits\System\Response;

class ControllerBase extends Controller
{
    use Response;
    protected $settings = [];

    public function initialize()
    {
        $this->settings = [
            "mySetting" => "value",
        ];
        if (strtolower($this->getName()) == strtolower('App-Controllers-Test-index-qx2')) {
            if ($this->request->isPost()) {
                return $this->dispatcher->forward([
                    'namespace' => 'App\\Controllers',
                    'controller' => 'error',
                    'action' => 'json',
                    'params' => [500, "测试错误"],
                ]);
            } else {
                return dispatch_error(500, "测试错误！");
            }
        }
    }

    public function getName()
    {
        $namespace = $this->router->getNamespaceName();
        $controller = $this->router->getControllerName();
        $action = $this->router->getActionName();

        $name = str_replace('\\', '-', $namespace);
        $name .= '-' . str_replace('\\', '-', $controller);
        $name .= '-' . str_replace('\\', '-', $action);

        return $name;
    }
}
