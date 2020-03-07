<?php

namespace App\controllers;

use App\bases\Controller;

abstract class SiteController extends Controller
{
    /**
     * SiteController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->init();
    }

    protected function init()
    {
        if (isset($_COOKIE['user_admin']) && $_COOKIE['user_admin'] == $_ENV['login']) {
            $this->setData('is_admin', 1);
        }
    }
}
