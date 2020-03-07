<?php

namespace App\bases;

use App\controllers\ErrorController;
use Exception;

class App
{
    private $routers = [];
    private $url = [];
    private $languages = [];
    private $lang;

    public function __construct($routers)
    {
        $this->routers = $routers;
        $parserUri = parse_url(strtolower($_SERVER['REQUEST_URI']));
        $request = $parserUri['path'] != '/' ? trim($parserUri['path'], '/') : 'index';
        $requestParams = explode('/', $request);
        $this->url['request'] = $request;
        $this->url['params'] = $requestParams;
    }


    public function run()
    {
        $controller = ErrorController::class;
        $method = 'exec';
        $regx = '#{(.+):(.+)}#';
        $routes = $this->routers;
        $argv = [];

        // обходим все маршруты роутера
        foreach ($routes as $pattern => $classname) {

            // разбиваем паттерн на лексемы и подготовлеваем для поиска маршрута
            $patternSlice = explode('/', $pattern);
            $argv = [];

            foreach ($patternSlice as $key => $value) {
                $char = substr($value, 0, 1);

                if (preg_match($regx, $value, $matches)) {

                    // если в роутере указан именованный параметр, обрабатываем его
                    // оставляем только параметр без имени (регулярное выражение)

                    $patternSlice[$key] = ($char == '[') ? "(/{$matches[2]})?" : '/' . $matches[2];

                    if (isset($this->url['params'][$key])) {
                        $argv[$matches[1]] = $this->url['params'][$key];
                    }
                } elseif (preg_match('#({)(\w+)(})?#', $value, $matches)) {
                    $patternSlice[$key] = ($char == '[') ? "(/\w+)?" : "/\w+";

                    if (isset($this->url['params'][$key])) {
                        $argv[$matches[2]] = $this->url['params'][$key];
                    }
                } else {

                    $sep = $key == 0 ? null : '/';

                    if ($char == '[') {
                        $patternSlice[$key] = '(' . $sep . trim($value, '[,]') . ')?';
                    } else {
                        if (substr($value, -1) == ']') {
                            $patternSlice[$key] = '(' . $sep . trim($value, '[,]') . ')?';
                        } else {
                            $patternSlice[$key] = $sep . trim($value, '[,]');
                        }

                    }
                }
            }

            $pattern = ltrim(implode('', $patternSlice), '/');
            $request = implode('/', $this->url['params']);

            if (preg_match("#^{$pattern}$#", $request)) {
                if (!is_subclass_of($classname[0], 'App\bases\Controller')) {
                    throw new Exception('The called class is not an inheritor App\bases\Controller');
                }

                if (!is_null($classname[1])) $method = $classname[1];
                $controller = $classname[0];
                break;
            }
        }

        $argv['request'] = $this->url['request'];
        $run = new $controller();
        $run->$method($argv);
    }
}
