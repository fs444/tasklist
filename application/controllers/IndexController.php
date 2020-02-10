<?php

    namespace controllers;

    require_once 'application/models/DatabaseModel.php';
    require_once 'application/models/TaskModel.php';

    use models\DatabaseModel;
    use models\TaskModel;

    class IndexController
    {
        //отображение главной страницы
        public function indexAction() {
            session_start();

            global $conn;

            DatabaseModel::connect();

            $task = new TaskModel();

            //формируем пагинацию
            /*
            $task_per_page - колво записей для вывода
            $num_first_task - с какой записи выводить
            $total - всего записей
            $page - текущая страница
            $str_pag = колво страниц для пагинации
            */

            //текущая страница
            if (isset($_GET['page'])) {
              $page = $_GET['page'];
            } else {
              $page = 1;
            }

            $task_per_page = 3;

            $num_first_task = ($page * $task_per_page) - $task_per_page;

            $result_page_count = $conn->query("select count(*) as count_pages from tasks");
            $row_page_count = mysqli_fetch_array($result_page_count);
            $total = $row_page_count['count_pages'];

            $str_pag = ceil($total / $task_per_page);

            //с какого задания отображаем список заданий в таблице
            $sort_task = " limit $num_first_task, $task_per_page";

            //sort_by - по какому столбцу сортируем
            if ($_GET['sort'] != '') {
              $sort = $_GET['sort'];

              $sort_by = " order by $sort " . $_GET['sort_order'];
            } else {
              $sort_by = '';
            }

            $result = $conn->query("select * from tasks" . $sort_by . $sort_task);

            return require 'application/views/index.phtml';
        }

    }
