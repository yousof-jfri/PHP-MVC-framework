<?php

namespace App;

use Core\Router;

$router = new Router();

$router->add('/', 'HomeController@index');
$router->add('/series', ['SeriesController', 'index']);
$router->add('/series/{title}/episode/{id}', 'SeriesController@allEpisodes');

return $router;