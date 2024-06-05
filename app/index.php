<?php

use Service\Session;
use Service\View;

require_once 'includes.php';

Session::instance()->start();
Session::instance()->regenerate();

View::render('views/base.php');


