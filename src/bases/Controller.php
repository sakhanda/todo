<?php

namespace App\bases;

use App\components\EntityHub;

abstract class Controller
{

    protected $container;
    protected $errors = [];
    protected $successes = [];
    protected $data = [];


    public function __construct()
    {
        if (isset($_SESSION['successes'])) {
            $this->setData('successes', $_SESSION['successes']);
            unset($_SESSION['successes']);
        }

        if (isset($_SESSION['errors'])) {
            $this->setData('errors', $_SESSION['errors']);
            unset($_SESSION['errors']);
        }
    }


    abstract public function exec($argv);


    public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }



    public function getData($key = null)
    {
        if ($key) {
            return isset($this->data[$key]) ? $this->data[$key] : null;
        } else {
            return $this->data;
        }
    }
}