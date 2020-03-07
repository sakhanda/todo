<?php


namespace App\controllers;


use App\bases\Container;
use Twig\Environment;

class InputController extends SiteController
{

    public function exec($argv)
    {

        if (isset($_COOKIE['user_admin']) && $_COOKIE['user_admin'] == $_ENV['login']) {
            setcookie('user_admin', $_ENV['login'], time() + 3600 * 24);
            header('location: /');
            die();
        }

        if (isset($_POST['submit'])) {
            $inputLogin = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
            $inputPassword = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);



            if (!$inputLogin || !$inputPassword) {
                $this->setData('errors', ['Введите логин и пароль!']);
            } else {
                $login = $_ENV['login'];
                $password = $_ENV['password'];

                if ($password == $inputPassword && $login == $inputLogin) {
                    setcookie('user_admin', $_ENV['login'], time() + 3600 * 24);
                    header('location: /');
                    die();
                } else {
                    $this->setData('errors', ['Доступ запрещен!']);
                }
            }
        }

        /** @var Environment $twig */
        $twig = Container::getInstance()->get('twig');
        $twig->display('site/input.twig', $this->getData());
    }
}
