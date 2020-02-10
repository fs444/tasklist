<?php

namespace models;

class AuthorizationModel
{
    //очистка авторизации в сессии
    public function clearLogin() {
        $_SESSION['user_name'] = '';
    }

    //проверка авторизации
    public function checkAuthData($user_name, $password) {
        global $conn;

        $login_errors = '';

        $result = $conn->query("select * from users where user_name = '$user_name'");

        if ($result->num_rows > 0) {
            $row = mysqli_fetch_array($result);

            if ($password == $row['password']) {
                $_SESSION['user_name'] = $_POST['user_name'];
            } else {
                $login_errors .= '<p>введите правильный пароль</p>';
            }
        } else {
            $login_errors .= '<p>логина не существует</p>';
        }

        return $login_errors;
    }
}
