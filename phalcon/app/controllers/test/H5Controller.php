<?php

namespace App\Controllers\Test;

use App\Controllers\Test\ControllerBase;

class H5Controller extends ControllerBase
{

    public function indexAction()
    {

    }

    public function particlesAction()
    {
        return $this->view->render('test/h5', 'particles');
    }

}

