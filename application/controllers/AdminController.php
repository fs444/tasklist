<?php

namespace controllers;

require_once 'application/models/TaskModel.php';
require_once 'application/models/DatabaseModel.php';

use models\TaskModel;
use models\DatabaseModel;

class AdminController
{
    //вход в систему с логином и паролем
    public function loginAction() {
        session_start();

        return require('application/views/login.phtml');
    }

    //редактирование задания
    public function editAction() {
        session_start();

        global $conn;

        DatabaseModel::connect();

        $result = $conn->query("select * from tasks where id = " . $_GET['task_id']);

        $row = mysqli_fetch_array($result);

        if ($row['status'] == 'done') {
            $status_checked = 'checked';
        } else {
            $status_checked = '';
        }

        return require('application/views/edit_as_admin.phtml');
    }

    //сохранение задания
    public function saveAction() {
        session_start();

        if ($_SESSION['user_name'] == '') {
            return require('application/views/need_login.phtml');
        } else {
            global $conn;

            DatabaseModel::connect();

            $task = new TaskModel();

            $text_task = $task->protectXssInTaskText($_POST['text_task']);

            //проверяем, менялся ли текст задачи администратором
            $exist_task_text = $conn->query("select * from tasks where id = " . $_POST['task_id']);
            $row = mysqli_fetch_array($exist_task_text);

            if ($row['task_text'] == $text_task) {
                $same_task_text = 1;
            } else {
                $same_task_text = 0;
            }

            if ($same_task_text) {
                //проверям, была ли уже отметка о редактировании
                $was_edited = $conn->query("select * from tasks where id = " . $_POST['task_id']);
                $row_was_edited = mysqli_fetch_array($was_edited);

                //определим статус задания
                if ($_POST['task_done'] == 'done') {
                    $task_done = 'done';
                } else {
                    $task_done = 'wait';
                }

                if ($row_was_edited['admin_edited']) {
                    $task->updateTask($text_task, $task_done, 1, $_POST['task_id']);
                } else {
                    $task->updateTask($text_task, $task_done, 0, $_POST['task_id']);
                }
            } else {
                $task->updateTask($text_task, $_POST['task_done'], 1, $_POST['task_id']);
            }

            return require('application/views/save_task.phtml');
        }
    }
}
