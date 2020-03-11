<?php
ini_set('display_errors', true);
ini_set('error_reporting', E_ERROR);

session_start();

/** @var string $path  */
use App\bases\App;

require_once __DIR__ . '/bootstrap.php';

try {
    $app = new App(include __DIR__ . '/app/routes.php');
    $app->run();
} catch (Exception $e) {
    echo $e->getMessage();
    echo '<br>';
    echo $e->getTraceAsString();
}
