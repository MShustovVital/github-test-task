<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $container->parameters()->set('GITHUB', [
        'host' => '%env(resolve:GITHUB_HOST)%',
        'token'=>'%env(resolve:GITHUB_TOKEN)%',
        'username'=>'%env(resolve:GITHUB_USERNAME)%'
    ]);
};
