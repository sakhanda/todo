<?php

use App\bases\Container;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

define('ROOT', dirname(__FILE__));

require_once ROOT . '/vendor/autoload.php';
require_once ROOT . '/src/bases/Container.php';

$di = Container::getInstance();

// Doctrine ORM configuration
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration([ROOT . "/src/entities"], $isDevMode);

// database configuration parameters
$conn = [
    'driver' => 'pdo_sqlite',
    'path' => ROOT . '/db/db.sqlite'
];

$entityManager = EntityManager::create($conn, $config);
$di->set('em', $entityManager);

//.ENV
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


// Twig
$loader = new FilesystemLoader(ROOT . '/view');
$twig = new Environment($loader, array(
    //'cache' => ROOT . '/view/cache',
    'cache' => false,
));
$di->set('twig', $twig);


function d($data, $die = true)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    if ($die) {die();}
}

function getCSRF()
{
    return $_SESSION['csrf_token'] =
        substr(str_shuffle('qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM'), 0, 10);
}
