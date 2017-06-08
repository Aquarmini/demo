<?php

namespace App\Controllers\Test;

use App\Controllers\Test\Controller;

class H5Controller extends Controller
{

    public function indexAction()
    {
        dump($_SERVER);
    }

    public function particlesAction()
    {
        return $this->view->render('test/h5', 'particles');
    }

}

