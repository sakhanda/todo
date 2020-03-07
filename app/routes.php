<?php

use App\controllers\AddController;
use App\controllers\ChartController;
use App\controllers\DashboardController;
use App\controllers\DashboardDataController;
use App\controllers\EditController;
use App\controllers\IndexController;
use App\controllers\InputController;
use App\controllers\OutputController;
use App\controllers\SettingController;
use App\controllers\SortController;

return [
    // frontend
    'index' => [IndexController::class, null],
    'input' => [InputController::class, null],
    'add' => [AddController::class, null],
    '{page:[0-9]+}' => [IndexController::class, null],
    'output' => [OutputController::class, null],
    'edit/{task:[0-9]+}' => [EditController::class, null],
    'sort' => [SortController::class, null]
];
