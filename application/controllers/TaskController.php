<?php

namespace controllers;

require_once 'application/models/TaskModel.php';
require_once 'application/models/DatabaseModel.php';

use models\TaskModel;
use models\DatabaseModel;

class TaskController
{
    public $error_message = "";

    //страница для создания новой задачи
    public function createAction() {
        return require('application/views/create_task.phtml');
    }

    //добавление новой задачи
    public function addAction() {
        global $conn;

        DatabaseModel::connect();

        $task = new TaskModel();

        $text_task = $task->protectXssInTaskText($_POST['text_task']);

        $this->error_message = $task->validateTaskData($_POST['user_name'], $_POST['user_email'], $text_task);

        if ($this->error_message == '') {
            $task->addTask($_POST['user_name'], $_POST['user_email'], $text_task);

            return require('application/views/add_task.phtml');
        } else {
            return require('application/views/create_task.phtml');
        }
    }
}
