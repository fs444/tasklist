<?php

namespace models;

class TaskModel
{
    //добавление задания
    public function addTask($user_name, $user_email, $task_text) {
        global $conn;

        $result = $conn->query("insert into tasks values (null, '$user_name', '$user_email', '$task_text', null, 0)");
    }

    //защита от XSS в тексте задания
    public function protectXssInTaskText($text_task) {
        $edited_text_task = strip_tags($text_task);
        $edited_text_task = htmlentities($edited_text_task, ENT_QUOTES, "UTF-8");
        $edited_text_task = htmlspecialchars($edited_text_task, ENT_QUOTES);

        return $edited_text_task;
    }

    //обновление задания
    public function updateTask($text_task, $task_done, $admin_edited, $task_id) {
        global $conn;

        $result = $conn->query("update tasks set task_text = '" . $text_task . "',
                                  status = '$task_done',
                                  admin_edited = $admin_edited
                                  where id = $task_id");
    }

    //проверка данных при добавлении задания
    public function validateTaskData($user_name, $user_email, $text_task) {
        if (empty($user_name) || empty($user_email)) {
            $error_message .= "<p>укажите имя и email</p>";
        }

        $email_is_valid = preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i', $user_email);

        if ($email_is_valid == false) {
            $error_message .= "<p>введите правильный email</p>";
        }

        if (empty($text_task)) {
            $error_message .= "<p>укажите текст задачи</p>";
        }

        return $error_message;
    }

    //изменение параметров в url при сортировке списка заданий на главной странице
    public function changeUrlParam($url, $varname, $value = NULL) {
        $urlinfo = parse_url($url);

        $get = (isset($urlinfo['query']))
             ? $urlinfo['query']
             : '';

        parse_str($get, $vars);

        if (!is_null($value))        // одновременно переписываем переменную
            $vars[$varname] = $value; // либо добавляем новую
        else
            unset($vars[$varname]); // убираем переменную совсем

        $new_get = http_build_query($vars);

        $result_url =   (isset($urlinfo['scheme']) ? "$urlinfo[scheme]://" : '')
                    . (isset($urlinfo['host']) ? "$urlinfo[host]" : '')
                    . (isset($urlinfo['path']) ? "$urlinfo[path]" : '')
                    . ( ($new_get) ? "?$new_get" : '')
                    . (isset($urlinfo['fragment']) ? "#$urlinfo[fragment]" : '')
                    ;

        return $result_url;
    }

}
