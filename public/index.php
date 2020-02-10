<?php

    chdir(dirname(__DIR__));

    require_once 'application/controllers/IndexController.php';
    require_once 'application/controllers/TaskController.php';
    require_once 'application/controllers/AdminController.php';
    require_once 'application/controllers/AuthorizationController.php';

    if (!isset($_GET['controller'])) {
        $controllerName = 'controllers\\IndexController';
        $action         = 'indexAction';
    }
    else {
        $controllerName     = 'controllers\\' . ucfirst($_GET['controller']) . 'Controller';
        $action             = $_GET['action'] . 'Action';
    }

    $controller = new $controllerName();

    return $controller->$action();
