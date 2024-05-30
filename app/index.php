<?php require_once 'includes.php';

var_dump(\Service\Session::new()->getAll());
\Service\View::render('views/base.php');

