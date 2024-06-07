<?php

namespace Controller;

use Service\View;

class HomeController
{

    public function index()
    {
        View::new()->render('views/templates/home.php');
    }

}
