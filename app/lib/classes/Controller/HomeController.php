<?php

namespace Controller;

use Service\View;

class HomeController
{

    public function index()
    {
        View::render('views/templates/home.php');
    }

}
