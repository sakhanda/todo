<?php

namespace App\controllers;

use App\bases\Container;
use App\entities\Task;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Twig\Environment;

class IndexController extends SiteController
{
    public function exec($argv)
    {
        /** @var EntityManager $em */
        $em = Container::getInstance()->get('em');

        $list = $em->getRepository(Task::class)->findAll();

        $page = $argv['page'] ?: 1;
        $limit = 3;
        $sortField = isset($_COOKIE['sort_field']) ? strtolower($_COOKIE['sort_field']) : 'id';
        $sortType = isset($_COOKIE['sort_type']) ? strtoupper($_COOKIE['sort_type']) : 'DESC';
        $dql = 'SELECT t FROM App\entities\Task t ORDER BY t.' . $sortField . ' ' . $sortType;
        $query = $em->createQuery($dql)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $paginator = new Paginator($query);
        $count = count($paginator);
        $allPage = ceil($count / $limit);
        $this->setData('list', $paginator);
        $this->setData('CSRF', getCSRF());
        $this->setData('paginator', [
            'allPage' => $allPage,
            'page' => $page,
            'startPage' => ($page > 5) ? $page - 5 : 1,
            'link' => $_ENV['host'],
            'length' => 10
        ]);

        $this->setData('nameCol', $this->LinkGenerator('Name'));
        $this->setData('emailCol', $this->LinkGenerator('Email'));
        $this->setData('statusCol', $this->LinkGenerator('Status'));

        /** @var Environment $twig */
        $twig = Container::getInstance()['twig'];
        $twig->display('site/index.twig', $this->getData());
    }

    protected function LinkGenerator($name)
    {
        $sortField = isset($_COOKIE['sort_field']) ? ucfirst($_COOKIE['sort_field']) : null;
        $sortType = isset($_COOKIE['sort_type']) ? $_COOKIE['sort_type'] : null;

        if (!$sortField || $sortField != $name) {
            return "<a href='/sort?n=" . strtolower($name) . "&t=a'>{$name}</a>";
        }

        if ($sortType == 'asc') {
            return "<a href='/sort?n=" . strtolower($name) . "&t=d'>{$name} <i class=\"fa fa-sort-asc\" aria-hidden=\"true\"></i></a>";
        } else {
            return "<a href='/sort?n=" . strtolower($name) . "&t=n'>{$name} <i class=\"fa fa-sort-desc\" aria-hidden=\"true\"></i></a>";
        }
    }
}
