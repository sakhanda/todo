<?php


namespace App\controllers;


use App\bases\Container;
use App\entities\Task;
use Doctrine\ORM\EntityManager;
use Twig\Environment;

class AddController extends SiteController
{

    public function exec($argv)
    {
        if (isset($_POST['submit'])) {
            $this->submit($_POST);
        }

        /** @var EntityManager $em */
        $em = Container::getInstance()->get('em');

        /** @var Environment $twig */
        $twig = Container::getInstance()['twig'];
        $twig->display('site/add.twig', $this->getData());
    }

    protected function submit($post)
    {
        $user_name  = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
        $user_email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
        $user_task  = htmlentities(trim(filter_input(INPUT_POST, 'task')));


        if (!$user_email || !$user_name || !$user_task) {
            $this->setData('errors', ['Заполните все поля!']);
            $this->setData('post', $post);
            return;
        }

        if (!filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
            $this->setData('errors', ['Введите корректный Email!']);
            $this->setData('post', $post);
            return;
        }


        /** @var EntityManager $em */
        $em = Container::getInstance()->get('em');
        $task = new Task();
        $task->setName($user_name);
        $task->setEmail($user_email);
        $task->setTask($user_task);
        $task->setStatus(0);
        $task->setEdited(0);

        $em->persist($task);
        $em->flush();

        header('location: /');
        die();
    }
}
