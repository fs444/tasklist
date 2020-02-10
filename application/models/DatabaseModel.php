<?php

namespace models;

class DatabaseModel
{
    //подключение к базе данных
    public static function connect() {
        global $conn;
        $conn = new \mysqli("localhost", "root", "", "mvc_site_db");

        if ($conn->connect_error) {
            die("ошибка подключения к бд" . $conn->connect_error);
        }
    }
}
