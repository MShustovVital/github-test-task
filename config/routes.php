<?php

use App\Controller\GithubRepositoryController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
	$routes->add('repository_index', '/repository')
		->controller([GithubRepositoryController::class, 'index']);
};
