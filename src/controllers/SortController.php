<?php


namespace App\controllers;


class SortController extends SiteController
{

    public function exec($argv)
    {
        $field = isset($_GET['n']) ? $_GET['n'] : null;
        $type = isset($_GET['t']) ? $_GET['t'] : null;

        if ($field && $type) {
            if ($type != 'n') {
                setcookie('sort_field', $field);
                setcookie('sort_type', $type == 'a' ? 'asc' : 'desc');
            } else {
                setcookie('sort_field', '');
                setcookie('sort_type', '');
            }
        }

        header('location: /');
        die();
    }
}
