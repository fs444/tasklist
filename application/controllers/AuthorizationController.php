<?php

namespace controllers;

require_once 'application/models/DatabaseModel.php';
require_once 'application/models/AuthorizationModel.php';

use models\DatabaseModel;
use models\AuthorizationModel;

class AuthorizationController
{
    //проверка входа в систему
    public function authAction() {
        session_start();

        global $conn;

        DatabaseModel::connect();

        $authorization = new AuthorizationModel();

        //проверяем совпадение логина и пароля
        $login_errors = $authorization->checkAuthData($_POST['user_name'], $_POST['user_password']);

        return require('application/views/check_login.phtml');
  }

    //выход из системы
    public function logoutAction() {
        session_start();

        $authorization = new AuthorizationModel();
        $authorization->clearLogin();

        return require('application/views/logout.phtml');
    }
}
