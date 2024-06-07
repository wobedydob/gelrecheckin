<?php

require_once 'includes.php';

session()->start();
session()->regenerate();

view()->render('views/base.php');


