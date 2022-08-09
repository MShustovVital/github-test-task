<?php

use App\Services\Github\Contracts\GithubService;
use App\Services\Github\GithubRestApiService;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator) {
	$configurator->import('github.php');
	$services = $configurator->services()
		->defaults()
		->autowire()
		->autoconfigure();
	$services->load('App\\', '../src/*')
		->exclude('../src/{DependencyInjection,Entity,Tests,Kernel.php}');
	$services->set(GithubService::class, GithubRestApiService::class);
	$services->set(Client::class)
		->arg('$config', ['verify' => false])
		->public();
	$services->set(Container::class);
};
