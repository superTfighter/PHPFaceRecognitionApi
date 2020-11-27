<?php

$container = $app->getContainer();

// Set up core dependencies
$dependencies = $container->get('settings')['modules']['System']['dependencies'];

// Sort dependencies by weight
array_multisort(array_column($dependencies, 'weight'), $dependencies);

// Load dependencies
foreach ($dependencies as $key => $value) {
    if ($value['enabled'] == TRUE) {
        require __DIR__ . '/'. $key .'/dependencies.php';
        if(file_exists(__DIR__ . '/'. $key .'/routes.php')) {
            require __DIR__ . '/'. $key .'/routes.php';
        }
    }
}