<?php

\Controller\RouteController::addRoute('/', 'views/templates/home.php', 'Home');
\Controller\RouteController::addRoute('/404', 'views/templates/404.php', '404');
\Controller\RouteController::addRoute('/info', 'info.php', 'info');