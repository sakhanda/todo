<?php


namespace App\controllers;


use App\bases\Container;
use App\bases\Controller;
use Doctrine\ORM\EntityManager;
use EstateTypeEntity;
use UserEntity;
use Twig\Environment;

class ErrorController extends Controller
{

    public function exec($argv)
    {
        /** @var Environment $twig */
        $twig = Container::getInstance()['twig'];
        $twig->display('site/error.twig');
    }
}