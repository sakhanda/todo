<?php


namespace App\controllers;


class OutputController extends SiteController
{

    public function exec($argv)
    {
        setcookie('user_admin', '', time() - 3600);
        header('location: /');
        die();
    }
}
