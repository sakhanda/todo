<?php


namespace App\controllers;


use App\bases\Container;
use App\entities\Task;
use Doctrine\ORM\EntityManager;
use Twig\Environment;

class EditController extends SiteController
{

    protected function init()
    {
        parent::init();
        if (!isset($_COOKIE['user_admin']) || $_COOKIE['user_admin'] != $_ENV['login']) {
            header('location: /input');
            die();
        }
    }

    public function exec($argv)
    {
        if (isset($_POST['submit'])) {
            $this->submit($argv);
        }

        /** @var EntityManager $em */
        $em = Container::getInstance()->get('em');

        $task = $em->find(Task::class, $argv['task']);
        if (!$task) {
            header('location: /');
            die();
        }
        $this->setData('post', $task);
        $this->setData('update', true);

        /** @var Environment $twig */
        $twig = Container::getInstance()['twig'];
        $twig->display('site/add.twig', $this->getData());
    }

    protected function submit( $argv)
    {
        $user_task = htmlentities(trim(filter_input(INPUT_POST, 'task')));

        /** @var EntityManager $em */
        $em = Container::getInstance()->get('em');
        /** @var Task $task */
        $task = $em->find(Task::class, $argv['task']);

        $status = isset($_POST['status']) ? $_POST['status'] : 0;
        $strcmp = strcmp($task->getTask(), $user_task);
        $hasUpdate = false;

        if ($strcmp !== 0) {
            $task->setTask($user_task);
            $task->setEdited(1);
            $hasUpdate = true;
        }

        if ($status != $task->getStatus()) {
            $task->setStatus($status);
            $hasUpdate = true;
        }

        if ($hasUpdate) {
            $em->flush($task);
            $this->setData('successes', ['Данные обновленны!']);
        } else {
            $this->setData('successes', ['Обновление данных НЕ требуется!']);
        }
    }
}
